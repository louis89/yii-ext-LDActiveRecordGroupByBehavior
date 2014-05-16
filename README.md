A behavior that generates SQL group conditions for CActiveRecords.
======================

This behavior provides a quick way to generate fully quoted and aliased SQL group strings for CActiveRecords. 

Usage
--------

There are 3 ways this behavior may be used.

1. Call the static method generateARGroupBy() passing a CActiveRecord instance and a string or array of column(s). The method will return the group by string for that CActiveRecord and column(s).
2. Attach this behavior to a CActiveRecord and call the generateGroupBy() method passing it a string or array of column(s). This method works exactly the same way as the previous static method except that the behavior's owner is used as the CActiveRecord parameter and therefor does not need to be explicitly passed to this method.
3. Attach this behavior to a CActiveRecord and call the groupBy() method passing it a string or array of column(s). This method will generate an SQL group string like in the previous method, but instead of returning that group string the CActiveRecord it self is returned and the group string is applied to the CActiveRecord's current CDbCriteria instead. 

Requirements 
------------

Yii 1.1.1 or above