# Introduction #

In the AKSW-group we have a lot of different topics currently researched by a great number of contributors. These topics present perfect opportunities for students of both bachelor and master studies to get in touch with the AKSW-group and get an insight on current research by writing their bachelor and master thesis on these topics. To streamline the way of presenting these topics to our students via the AKSW website we decided to use google documents on google drive.

# Making a topic document accessible to students #

1. Use your own google account to write a topic proposal and store it in your drive.
2. Change the access-setting so that everyone with a link can see your document.
3. You can preview your document by downloading the .pdf.
4. Share the document with the AKSW-googledoc-viewer Project ID (email adress) that is to be accounced later or add the document to our AKSW-topics folder.
5. Add constraint properties to your document description (see below).

# Topic Property System #

To implement a basic search system, properties need to be added to the description field of your document. Please use syntactically correct JSON to do so, e.g.

    {
    "type" : "Bachelor Thesis",
    "status" : "open",
    "supervisor":
    ["Supervisor 1","Supervisor 2"]
    }

The following properties are recommended:

| property | status 1 | status 2 | status 3|status 4|
|---|---|---| ---| --- |
| status | open | assigned | closed | --- |
| type | Bachelor Thesis | Master Thesis | Project | Dissertation |
| urgency | high | normal | --- |
| supervisor | use aksw.org Nametag "FirstnameLastname" |
