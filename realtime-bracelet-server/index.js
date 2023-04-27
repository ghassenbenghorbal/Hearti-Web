const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
  cors: {
    origin: "http://localhost:8000",
    methods: ["GET", "POST"]
  }
});

app.get("/", (req, res) => {
  res.send("<h1>Hello world</h1>");
});

io.on("connection", async function(socket) {
  const validBraceletID = 'A1B2C3'
  const { braceletId } = socket.handshake.auth;

  if (braceletId != validBraceletID) {
    const error = new Error('Invalid bracelet ID');
    socket.emit('connection_error', { message: error.message });
    socket.disconnect(true);
    return;
  }

  console.log("a user connected");

  socket.broadcast.emit("userConnected", {
    channelID: socket.id,
    username: socket.username,
  });

  const users = [];
  for (let [id, socket] of io.of("/").sockets) {
    users.push({
      channelID: id,
      username: socket.username,
    });
  }
  socket.emit("users", users);

  socket.on("disconnect", () => {
    console.log("user disconnected");
    socket.broadcast.emit("userDisconnected", {
      channelID: socket.id,
      username: socket.username,
    });
  });

});

server.listen(3001, () => {
  console.log("listening on *:3001");
});
