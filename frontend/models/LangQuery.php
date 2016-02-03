<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Lang2]].
 *
 * @see Lang2
 */
class LangQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Lang2[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Lang2|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}