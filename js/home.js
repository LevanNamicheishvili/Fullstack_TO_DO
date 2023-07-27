// Function to update the clock
function updateClock() {
    const now = new Date();
    const year = now.getFullYear().toString().padStart(2, '0');
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');
    const weekDays = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    const weekDay = weekDays[now.getDay()];
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
  
    // Set content of date
    document.querySelector('.date').innerHTML = `${year}-${month}-${day} ${weekDay}`;
  
    // Set content of hour
    document.querySelector('.hr').innerHTML = hours;
  
    // Set content of minute
    document.querySelector('.min').innerHTML = minutes;
  
    // Set content of second
    document.querySelector('.sec').innerHTML = seconds;
  }
  
  // Execute function updateClock() immediately
  updateClock();
  
  // Execute function updateClock() every 1 second (1000 milliseconds = 1 second)
  setInterval(updateClock, 1000);
  