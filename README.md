# Email Template Scaffold
A Rhubarb Scaffold that aspires to make the editing of email templates easy for users 

Theory behind it is that every TemplatedFile would have a Template Object behind it, which can be changed and modified to suit the end users needs. The Files only serve as defaults.

Each Email Template can have a parent, but the parent must support having children with a `{ChildContent}` flag somewhere in the template.
