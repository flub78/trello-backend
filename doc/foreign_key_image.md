# Foreign keys image

For table foreign keys, it is required to display the element value to the user. We need a string representation the the referenced elements.

This string can be used to select an element in the target table or to display its value in a human readable way. The primary key is usually not usable especially if it is an auto incremented integer.

The best approach is to define a unique human readable string to identify every elements.

* The string can be defined by the user
* or computed from others attributes.

Laravel computed attributes are not really usable in this context because they cannot be used in queries (you cannot query the database on information that the database does not know...).

It is more convenient to include a special column in the model, and to compute its value from others attributes if necessary.

By convention, I'll use image and image_short to uniquely identify elements in the table. The short version may be used when there is limited place on the display. 

These strings can be used to implement several types of display ans selector in the frontend.

To check: is it possible to use laravel validation on computed attributes ? 

    https://stackoverflow.com/questions/47168082/how-to-make-a-validation-on-a-calculated-field-in-laravel-5-5


## Notes

In some contexts, there is no need for the image to be non ambiguous, only to be unique inside the context.

Laravel is really flexible, it is possible to rename primary keys. changes are propagated ...
So I do not need an image for tables with string primary key.

## Convention

It is easier to let the backend manage foreign key images.
For each foreign_key value, the backend will add an image name with the name of the column plus "_image".

That way it is even possible for the backend to return context dependent values. For exemple a full unique name when there is no context, but a shorter name when part of the context is defined and there is no ambiguity.

## Questions

Should I define an image attribute in the referenced tables and do a join to include it in queries on other tables ?

or should I have additional computed attributes in the referencing table ?

With the first approach, the job is done only once.

