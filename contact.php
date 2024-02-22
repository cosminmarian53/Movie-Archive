<?php
  include 'includes/header.php';
?>

<!-- Content -->
<div class="container">
  <h1 class="mb-3">Contact</h1>
  <form>
    <!-- Email Input Field -->
    <div class="form-group">
      <label for="exampleFormControlInput1">Email</label>
      <input type="email" class="form-control" placeholder="Enter your email">
    </div>
    <!-- Full Name Input Field -->
    <div class="form-group">
      <label for="exampleFormControlInput1">Full Name</label>
      <input type="email" class="form-control" placeholder="Enter your full name">
    </div>
    <!-- Textarea Field -->
    <div class="form-group">
      <label>Message</label>
      <textarea class="form-control" id="" rows="3"></textarea>
    </div>
  </form>
  <!-- Submit Button -->
  <div class="btn-wrapper">
    <button type="submit" class="btn btn-std" name="contact-btn">Send message</button>
  </div>
</div>

<?php
  include 'includes/footer.php';
?>