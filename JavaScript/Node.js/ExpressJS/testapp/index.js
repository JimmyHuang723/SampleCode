const express = require('express')
const app = express()

app.get('/', function (req, res) {
  res.send('Hello World!')
})

app.get('/mongo', function (req, res) {
  var MongoClient = require('mongodb').MongoClient

  MongoClient.connect('mongodb://jimmy723:xxxjimmy723@care.hopto.org:27017/test', function (err, db) {
    if (err) throw err

    db.collection('test').find().toArray(function (err, result) {
      if (err) throw err
      
      result.forEach((member, index) => {
        console.log("-------------")
        console.log(member)
      })

     
    })
  })

  res.send('mongo!')
})

app.listen(3000, function () {
  console.log('Example app listening on port 3000!')
})


