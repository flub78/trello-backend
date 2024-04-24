 # API documentation

 This project is a framework to generate REST API, the user documentation has to be provided.

 One way to generate and publish the user documentation is to use Swagger. https://app.swaggerhub.com/

 This tool provides a graphical editor and a way to publish the documentation. It is based on a textual description that should be easy to generate from metadata and a template.

 By default public APIs can be accessed through a a public URL
 https://app.swaggerhub.com/apis/flub78/todoList/1.0.0#/

 The geting started documentation https://support.smartbear.com/swaggerhub/getting-started/

 Two approaches:
 * Design first, the documentation is written before the code.
 * Code first, in this case it is possible to import existing APIs.

In this project, most of the APIs are automatically generated from the metadata. The API documentation can also be partially automated, so we are more code first oriented. 

There is a tool to convert json to swagger snippets: https://roger13.github.io/SwagDefGen/

And the documentation reference: https://swagger.io/docs/specification/about/

## API documentation generation

The global API documentation is made of a global file containing:

* version
* description
* security policy
* global parameters (pagination, lang, etc.)

Then for all resources there is
* the path description
  * get
  * get/{id}
  * post
  * put/{id}
  * delete/{id}
* The data model

The path description is pretty standard and could be handled with a Mustache template mainly configured with the resource name.

The data model has to be derived from the table metadata. 

### Implementation note:

The difficulty is to iterate through the resources and generate both the path descriptions and the data schema.



 
