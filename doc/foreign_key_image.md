# Foreign keys image

When an object containing a foreign key is created or updated the user has to fill the foreign key field. Sometimes it is a string in which case the user could type it. But when it is an integer and the number of element in the target table is big we have to find a more convenient way.

The more convenient appraoch is to define a unique human readable string.

* The string can be defined by the user
* or computed from others attributes.

Il the string is contained into a table field named image and set by the user, it is relatively easy to check that the string is unique. The image field can even be used as primary key even if it not recommended to use application information as primary key (remember the spelling mistake in a last name and the difficulty to fix it).

If the string is computed, for exemple a flight made by a pilot at a certain time on some machine, the validation rules must gurantee that the computed string is unique.

So in our case selectors should use 
