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

io.use((socket, next) => {
  const username = socket.handshake.auth.username;
  if (!username) {
    return next(new Error("invalid username"));
  }
  socket.username = username;
  next();
});

io.on("connection", async function(socket) {
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

  socket.on("privateMessage", ({ content, to }) => {
    socket.to(to).emit("privateMessage", {
      content,
      from: socket.id,
    });
  });

});

server.listen(3000, () => {
  console.log("listening on *:3000");
});
