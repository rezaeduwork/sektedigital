import './bootstrap';
import 'flowbite';
import './command-tabs';

document.addEventListener('livewire:navigated', () => {
  initFlowbite();
})
