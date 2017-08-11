# Email Template Scaffold
A Rhubarb Scaffold that aspires to make the editing of email templates easy for users 

Theory behind it is that every TemplatedFile would have a Template Object behind it, which can be changed and modified to suit the end users needs. The Files only serve as defaults.

Each Email Template can have a parent, but the parent must support having children with a `{ChildContent}` element somewhere in the template.


## Future Ideas

* I'm looking to add support for Models being integrated into the Templates, and all the relationships following too.
* Possibly change out the templating engine to [https://www.smarty.net/](Smarty) to support complex templates with if statements and for loops
* Create special controls that allow very easy HTML editing of templates, with live previews of the whole relationship (i.e If a child is being edited, display how it looks in the parent(s))
