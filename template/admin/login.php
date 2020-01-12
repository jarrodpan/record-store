<form action="<?=self::rootPath();?>/login" method="post">
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control" placeholder="Enter username" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="password">Password:</label>
    <input type="text" class="form-control" placeholder="Enter password" name='password' id="password" required>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>