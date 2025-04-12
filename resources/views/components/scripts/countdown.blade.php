<script>
  function countdown(endTimestamp) {
    return {
      endTime: parseInt(endTimestamp, 10), // Convert the passed timestamp to an integer
      timeLeft: '',
      startCountdown() {
        const updateTimer = () => {
          const now = Math.floor(Date.now() / 1000); // Current time in Unix timestamp
          const difference = this.endTime - now;

          if (difference > 0) {
            const days = Math.floor(difference / 86400);
            const hours = Math.floor((difference % 86400) / 3600);
            const minutes = Math.floor((difference % 3600) / 60);
            const seconds = difference % 60;

            this.timeLeft = `${days > 0 ? days+'Hari ':''}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
          } else {
            this.timeLeft = '0d 00:00:00'; // When countdown ends
            clearInterval(this.interval);
          }
        };

        // Initial update
        updateTimer();

        // Update every second
        this.interval = setInterval(updateTimer, 1000);
      }
    };
  }

</script>
