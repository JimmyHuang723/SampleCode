function hello(req, res) {
  res.send('Hello World from func.js!')
}

function hello2(req, res) {
  res.send('Hello 2 World from func.js!')
}

module.exports = {
	               hello, 
	               hello2
	             };
