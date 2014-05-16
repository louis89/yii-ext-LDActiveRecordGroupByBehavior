<?php
/**
 * LDActiveRecordGroupByBehavior class file.
 *
 * @author Louis A. DaPrato <l.daprato@gmail.com>
 * @link https://lou-d.com
 * @copyright 2014 Louis A. DaPrato
 * @license The MIT License (MIT)
 * @since 1.0
 */

/**
 * This behavior provides a quick way to generate fully quoted and aliased SQL group string for CActiveRecords. 
 * 
 * There are 3 ways this behavior may be used.
 * 
 * 1. Call the static method generateARGroupBy() passing a CActiveRecord instance and a string or array of column(s). The method will return the group by string for that CActiveRecord and column(s).
 * 2. Attach this behavior to a CActiveRecord and call the generateGroupBy() method passing it a string or array of column(s). 
 * This method works exactly the same way as the previous static method except that the behavior's owner is used as the CActiveRecord parameter and therefor does not need to be explicitly passed to this method.
 * 3. Attach this behavior to a CActiveRecord and call the groupBy() method passing it a string or array of column(s). 
 * This method will generate an SQL group string like in the previous method, but instead of returning that group string the CActiveRecord it self is returned and the group string is applied to the CActiveRecord's current CDbCriteria instead. 
 * 
 * Requires Yii 1.1.1 or above
 * 
 * @author Louis A. DaPrato <l.daprato@gmail.com>
 *
 */
class LDActiveRecordGroupByBehavior extends CActiveRecordBehavior
{
	
	/**
	 * Generates a group string for an AR for use with CDbCriteria. Default columns is null meaning group by the AR's primary key(s)
	 *
	 * @param CActiveRecord The active record to generate the grouping for.
	 * @param mixed array or string of column(s) to group the AR by. If a string multiple columns can be delimited with a comma. Defaults to null meaning group by primary key(s)
	 * @return string A group string for use with a CDbCriteria
	 */
	public static function generateARGroupBy($activeRecord, $columns = null)
	{
		if($columns === null)
		{
			$cols = (array)$activeRecord->getTableSchema()->primaryKey;
		}
		else if(is_array($columns))
		{
			$cols = $columns;
		}
		else
		{
			$cols = preg_split('/\s*,\s*/', trim((string)$columns), -1, PREG_SPLIT_NO_EMPTY);
		}
	
		$db = $activeRecord->getDbConnection();
		foreach($cols as &$col)
		{
			$col = $db->quoteColumnName($activeRecord->getTableAlias().'.'.$col);
		}
		return implode(',', $cols);
	}
	
	/**
	 * Calls {@link LDActiveRecordGroupBehavior::generateARGroupBy()} passing it the AR that owns this behavior.
	 * 
	 * @param mixed array or string of column(s) to group the AR by. If a string multiple columns can be delimited with a comma. Defaults to null meaning group by primary key(s)
	 * @return string A group string for use with a CDbCriteria
	 */
	public function generateGroupBy($columns = null)
	{
		return self::generateARGroupBy($this->getOwner(), $columns);
	}
	
	/**
	 * Generates a group string for this AR and applies it to this AR's CDbCriteria
	 * @see LDActiveRecordGroupBehavior::generateGroupBy()
	 * @param mixed array or string of column(s) to group the AR by. If a string multiple columns can be delimited with a comma. Defaults to null meaning group by primary key(s)
	 * @return ActiveRecord The owner of this AR behavior to allow for method chaining
	 */
	public function groupBy($columns = null)
	{
		$owner = $this->getOwner();
		$owner->getDbCriteria()->mergeWith(array('group' => $this->generateGroupBy($columns)));
		return $owner;
	}
	
}