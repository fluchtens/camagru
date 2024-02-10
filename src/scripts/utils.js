const formatElapsedTime = (timeDiff) => {
  const timeComponents = timeDiff.split(":");
  const hours = parseInt(timeComponents[0], 10);
  const minutes = parseInt(timeComponents[1], 10);
  const seconds = parseInt(timeComponents[2], 10);

  const weeks = Math.floor(hours / 24 / 7);
  const days = Math.floor(hours / 24) % 7;

  if (weeks > 0) {
    return weeks + "w";
  } else if (days > 0) {
    return days + "d";
  } else if (hours > 0) {
    return hours + "h";
  } else if (minutes > 0) {
    return minutes + "min";
  } else if (seconds > 0) {
    return seconds + "s";
  } else {
    return "Now";
  }
};
