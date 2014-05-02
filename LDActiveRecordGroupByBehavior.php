<?php

/**
 * 
 * @author Louis DaPrato <l.daprato@gmail.com>
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