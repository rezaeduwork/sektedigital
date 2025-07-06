/**
 * Handles the command category tabs in the Add Command modal
 */
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tabs when add command modal is shown
  Livewire.on('addCommandModalShown', function() {
    setTimeout(initCommandTabs, 100);
  });

  function initCommandTabs() {
    const tabElements = document.querySelectorAll('#commandTabs button[data-tabs-target]');
    const tabContents = document.querySelectorAll('#commandTabsContent > div');

    // Set first tab as active by default
    if (tabElements.length > 0) {
      tabElements[0].classList.add('border-primary', 'text-primary');
      tabElements[0].classList.remove('border-transparent');

      const targetId = tabElements[0].getAttribute('data-tabs-target');
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        targetElement.classList.remove('hidden');
      }
    }

    // Add click event to all tab buttons
    tabElements.forEach(tab => {
      tab.addEventListener('click', function() {
        // Reset all tabs
        tabElements.forEach(el => {
          el.classList.remove('border-primary', 'text-primary');
          el.classList.add('border-transparent');
          el.setAttribute('aria-selected', 'false');
        });

        // Hide all tab contents
        tabContents.forEach(content => {
          content.classList.add('hidden');
        });

        // Activate selected tab
        this.classList.add('border-primary', 'text-primary');
        this.classList.remove('border-transparent');
        this.setAttribute('aria-selected', 'true');

        // Show selected tab content
        const targetId = this.getAttribute('data-tabs-target');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          targetElement.classList.remove('hidden');
        }
      });
    });
  }
});
