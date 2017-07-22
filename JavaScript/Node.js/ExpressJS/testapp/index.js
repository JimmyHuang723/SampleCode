const express = require('express')
const app = express()
var bodyParser = require('body-parser');
var multer = require('multer'); // v1.0.5
var upload = multer(); // for parsing multipart/form-data

app.use(bodyParser.json()); // for parsing application/json
app.use(bodyParser.urlencoded({ extended: true })); // for parsing application/x-www-form-urlencoded


app.get('/', function (req, res) {
  res.send('Hello World!')
})


app.post('/mongowrite', function (req, res) {
  var MongoClient = require('mongodb').MongoClient

  MongoClient.connect('mongodb://jimmy723:xxxjimmy723@care.hopto.org:27017/test', function(err, db) {
  if (err) throw err;

  //var myobj = { name: "Company Inc", address: "Highway 37" };
  var myobj = req.body;

  db.collection("test").insertOne(myobj, function(err, res) {
    if (err) throw err;

    console.log("1 record inserted");
    db.close();
  });
});

  res.send('done!');
});


app.get('/mongoread', function (req, res) {
  var MongoClient = require('mongodb').MongoClient

  MongoClient.connect('mongodb://jimmy723:xxxjimmy723@care.hopto.org:27017/test', function (err, db) {
    if (err) throw err
    
    db.collection('test').find().toArray(function (err, result) {
      if (err) throw err
      
      result.forEach((member, index) => {
        console.log("-------------")
        console.log(member)
      })
      
      res.json(result);
      db.close(); 
    })
  })
})



app.listen(3000, function () {
  console.log('Example app listening on port 3000!')
})


