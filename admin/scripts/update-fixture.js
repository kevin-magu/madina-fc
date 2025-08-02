document.addEventListener('DOMContentLoaded', () => {
  // Listen for click events on all update fixture buttons
  document.querySelectorAll('.update-fixture-btn').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // Prevent default navigation

      const confirmDone = confirm("Are you sure the game is complete?");
      if (confirmDone) {
        // Redirect to the href if confirmed
        window.location.href = this.getAttribute('href');
      }
    });
  });
});
