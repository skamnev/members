<?php

namespace frontend\components;

use backend\models\FieldsTypes;
use backend\models\MappingCategories;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;
use frontend\models\MembersProgress;
use frontend\models\MembersQuestionsAnswers;
use frontend\models\User;
use Yii;
use yii\base\Component;

/**
 * This component will do:
 * - retrieve mapping categories
 * - retrieve current step
 * - retrieve mapping questions according to the current step
 * - build steps according to the retrieved information
 *
 */
class MappingComponent extends Component
{
    private $categories;
    private $profileFields = ['firstname', 'lastname', 'weight', 'height', 'birthday'];
    private $stepsInformation = [];
    public $updateMappingCategory = false;

    public function init() {
        if (!Yii::$app->user->isGuest) {
            $this->checkMappingDuration();
        }
        parent::init();
    }

    public function checkMappingDuration() {
        $options[] = ['<>', 'duration', 0];
        $category = $this->getActiveCategories($options, true, false);

        $timeDaysDiff = $this->calcCategoryDuration($category);

        if ($timeDaysDiff >= $category['duration']) {
            $this->updateMappingCategory = $category['id'];
        }
    }

    public function calcCategoryDuration($category) {
        $memberProgress = MembersProgress::find()
                            ->where(['member_id' => Yii::$app->user->id, 'category_id' => $category['id']])
                            ->one();

        $timeDiff = 0;

        if ($memberProgress) {
            $lastTime = strtotime($memberProgress->updated_at);
            $nowTime = time();

            return $timeDiff = ($nowTime - $lastTime)/(60*60*24);
        }

        //return category duration in case we do not have progress saved
        return $category['duration'];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCategories($options = array(), $asArray = true, $all = true) {
        //if (empty($this->categories)) {
            $categories = MappingCategories::find()->orderBy('sort_order, name');
            if (!empty($options)) {
                $categories->where($options);
            }

            if ($asArray) {
                $categories->asArray();
            }

            if ($all == true) {
                $this->categories = $categories->all();
            } else {
                $this->categories = $categories->one();
            }
        //}

        return $this->categories;
    }

    public function getActiveCategories($options = [['is_active' => 1]], $asArray = true, $all = true) {
        //if (empty($this->categories)) {
            $options = array_merge(['and'], $options);
            $this->categories = $this->getCategories($options, $asArray, $all);
        //}

        return $this->categories;
    }

    public function process() {
        //$this->loadSteps();

        if (Yii::$app->request->post()) {
            if (isset($this->stepsInformation['view']) && $this->stepsInformation['view'] == 'profile') {
                $this->processProfile();
            } else if (isset($this->stepsInformation['view']) && $this->stepsInformation['view'] == 'questions') {
                $questionsAnswers = $this->stepsInformation['questionsAnswers'];
                if ($this->processQuestions($questionsAnswers)) {
                    $this->loadSteps(); //reload steps if saved
                }
            }
        }
    }

    private function processProfile () {
        $model = $this->stepsInformation['profileModel'];
        $model->setScenario('mapping');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->loadSteps(); //reload steps if saved
        } else {
            $this->stepsInformation['profileModel'] = $model;
        }
    }

    public function processQuestions ($questionsAnswers) {
        if ($questionsAnswers->load(Yii::$app->request->post()) && $questionsAnswers->validate()) {
            $questionsModel = new MappingQuestions();

            foreach ($questionsAnswers->attributes as $attribute_id => $attribute) {

                $attributesArray = explode('_', $attribute_id);

                //ignore attributes without underscores
                if (count($attributesArray) < 2) {
                    continue;
                }

                list($name, $id) = $attributesArray;

                if ($id > 0) {
                    list($name, $id) = $attributesArray;
                    if (!$model = MembersQuestionsAnswers::findOne(['question_id' => $id, 'member_id' => Yii::$app->getUser()->id])) {
                        $model = new MembersQuestionsAnswers();
                    }

                    $has_option = false;

                    if ($questionsModel = MappingQuestions::findOne($id)) {
                        $type_id = $questionsModel->type_id;
                        $fieldsModel = FieldsTypes::findOne($type_id);
                        $has_option = $fieldsModel->has_options;
                    }

                    $model->{$name} = $questionsAnswers->{$attribute_id};

                    if ($has_option) {
                        $model->value = '';
                        $model->option_id = '';
                        if ($optionsModel = MappingQuestionsToOptions::findOne($questionsAnswers->{$attribute_id})) {
                            $model->value = $optionsModel->title;
                            $model->option_id = $optionsModel->id;
                        }

                        if ($fieldsModel->has_other_field && $questionsModel->has_other && $name == 'value') {
                            $model->option_id = $questionsAnswers->{$attribute_id};
                        }

                    }

                    $model->question_id = $id;
                    $model->member_id = Yii::$app->getUser()->id;

                    $model->save();
                }
            }
            if (!empty($questionsModel->category_id)) {
                $this->saveProgress($questionsModel->category_id);
            }
            return true;
        }
        $this->stepsInformation['questionsAnswers'] = $questionsAnswers;

        return false;
    }

    public function saveProgress($categoryId)
    {
        /* initiate members progress data and save to database */
        $memberProgress = MembersProgress::find()->where(['category_id' => $categoryId, 'member_id' => Yii::$app->user->id])->one();

        //if member progress does not exist
        if (empty($memberProgress)) {
            //init model
            $memberProgress = new MembersProgress();

            $memberProgress->member_id = Yii::$app->user->id;
            $memberProgress->category_id = $categoryId;
        }

        //set/reset progress to 1
        $memberProgress->progress = 1;

        return $memberProgress->save();
    }

    public function getSteps() {
        if (empty($this->stepsInformation)) {
            $this->loadSteps();
        }

        return $this->stepsInformation;
    }

    public function loadSteps() {
        $this->stepsInformation = [];

        if (!$this->checkProfileFields()) {
            $this->stepsInformation['steps'][] = Yii::t('frontend', 'Profile');
            $this->stepsInformation['view'] = 'profile';
            $this->stepsInformation['profileModel'] = User::findOne(Yii::$app->user->id);
        } else {
            $categories = $this->getActiveCategories();

            foreach ($categories as $category) {

                if (!$this->checkCategoryAnswered($category['id'])) {
                    $this->stepsInformation['steps'][] = $category['translation']['name'];

                    $this->stepsInformation['view'] = 'questions';
                    $this->stepsInformation['currentCategory'] = $category;
                    $this->stepsInformation['questionsAnswers'] = new MembersQuestionsAnswers($category['id']);
                    break;
                } else {
                }
            }
        }
    }

    public function checkCategoryAnswered($id) {
        $requiredQuestions = MappingQuestions::find()->where(['and', ['category_id' => $id, 'is_required' => 1, 'is_active' => 1]]);

        if ($requiredQuestions->count()) {
            foreach($requiredQuestions->asArray()->all() as $question) {
                $answerExists = MembersQuestionsAnswers::find()->where(['question_id' => $question['id'], 'member_id' => Yii::$app->user->id])->exists();
                if (!$answerExists) {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkProfileFields () {
        if (!Yii::$app->user->isGuest) {
            $userModel = User::find()->where(['id' => Yii::$app->user->id]);

            foreach($this->profileFields as $field) {
                $userModel->andWhere(['!=', $field, '']);
            }

            return $userModel->asArray()->one();
        }
    }

    public function currentStep() {

    }
    
    public function getCategoryProgressDate($categoryId)
    {
        if (!Yii::$app->user->isGuest) {
            /* initiate members progress data and save to database */
            $memberProgress = MembersProgress::find()->where(['category_id' => $categoryId, 'member_id' => Yii::$app->user->id])->one();
            $memberProgressTime = 0;
            
            //if member progress does not exist
            if (!empty($memberProgress)) {
                $memberProgressTime = $memberProgress->updated_at;
            }
            
            return $memberProgressTime;
        }
        
        return false;
    }
}
