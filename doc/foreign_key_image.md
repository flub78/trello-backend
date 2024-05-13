# Foreign keys image

With foreign keys in a table, it is required to display the element value to the user. We need a string representation the the referenced elements.

This string can be used to select an element in the target table or to display its value in a human readable way. The primary key is usually not usable especially if it is an auto incremented integer.

The best approach is to define a unique human readable string to identify every elements.

* The string can be defined by the user
* or computed from others attributes.

Laravel computed attributes are not really usable in this context because they cannot be used in queries (you cannot query the database on information that the database does not know...).

It is more convenient to include a special column in the model, and to compute its value from others attributes if necessary.

By convention, I'll use string_id and string_id_short to uniquely identify elements in the table. The short version may be used when there is limited place on the display.

These strings can be used to implement several types of display ans selector in the frontend.

To check: is it possible to use laravel validation on computed attributes ? 

    https://stackoverflow.com/questions/47168082/how-to-make-a-validation-on-a-calculated-field-in-laravel-5-5

