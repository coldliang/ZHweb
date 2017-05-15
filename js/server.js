/**
 * Created by windows on 2017/5/1.
 */
var http = require('http');

http.createServer(function (request, respnse){

    respnse.writeHead(200,{'Content-Type': 'text/plain'});

    respnse.end('Hello World\n');
}).listen(8888);

console.log('Server running at http://127.0.0.1:8888/');