const db = require('../config/db');

exports.registerUser = (req, res) => {
  const { username, email, password } = req.body;
  db.query('INSERT INTO users (username, email, password) VALUES (?, ?, ?)', 
    [username, email, password], 
    (err, result) => {
      if (err) return res.status(500).send(err);
      res.status(201).send({ id: result.insertId, username, email });
    });
};
