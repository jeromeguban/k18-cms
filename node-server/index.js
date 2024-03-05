const app = require('express')(),
	https = require('http'),
	dotenv = require('dotenv'),
	fs = require("fs"),
	helmet = require("helmet"),
	Redis = require("ioredis")

dotenv.config()

app.use(helmet()) // Add Helmet as a middleware

const redis = new Redis({
	port: process.env.REDIS_PORT, // Redis port
	host:  process.env.REDIS_HOST, // Redis host
	password: process.env.REDIS_PASSWORD,
	db: 0,
})

const options = {
	key: fs.readFileSync("./sslcert/hammer.key"),
	cert: fs.readFileSync("./sslcert/hammer.crt")
};

// const server = https.createServer(options, app)
const server = https.createServer(app)


const io = require('socket.io')(server, {
		cors: {
			origin: "*",
			// origin: ["http://127.0.0.1:8081", "http://cms.hmr.ph", "https://cms.hmr.ph", "http://staging.hmrbid.com", "https://staging.hmrbid.com"],
			methods: ["GET", "POST"]
		}
	})


redis.psubscribe("system:*", (err, count) => {

})

// Event names are "pmessage"/"pmessageBuffer" instead of "message/messageBuffer".
redis.on("pmessage", (pattern, channel, message) => {
	let data = JSON.parse(message)
	io.emit(`${channel}`, data)
})


io.on('connection', (socket) => {

})


server.listen(process.env.PORT, () => {
  	console.log(`Listening on ${process.env.HOST}:${process.env.PORT}`)
})
