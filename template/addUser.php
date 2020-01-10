<form action="/DJJ/users/add" method="post">
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control" placeholder="Enter username" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="firstname">First Name:</label>
    <input type="text" class="form-control" placeholder="Enter first name" id="firstname" name="firstname" required>
  </div>
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" placeholder="Enter email" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="password">Password:</label>
    <input type="text" class="form-control" placeholder="Enter password" name='password' id="password" required>
  </div>
  <div class="form-group">
    <label for="access">Access Level:</label>
    <input type="number" class="form-control" maxlength="4" placeholder="Access level" name='access' id="access"  value="1000" required>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>