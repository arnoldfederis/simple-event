export const set = (key, value) => {
  return localStorage.setItem(key, JSON.stringify(value));
};

export const get = (key) => {
  try {
    return JSON.parse(localStorage.getItem(key));
  } catch (e) {
    return false;
  }
};

export const remove = (key) => {
  return localStorage.removeItem(key);
};

export const getDaysArray = (startDate, endDate) => {
  let dates = [];
  let currentDate = new Date(startDate);

  while (currentDate <= new Date(endDate)) {
    dates.push({
      name: null,
      date: new Date(currentDate),
      identifier: formatDate(currentDate),
      change_date_name: new Date(currentDate).getDate() === 1
    });

    currentDate.setDate(currentDate.getDate() + 1);
  }

  return dates;
}

export const formatDate = (date) => {
  let dateParse = new Date(date);
  let month = '' + (dateParse.getMonth() + 1);
  let day = '' + dateParse.getDate();
  let year = dateParse.getFullYear();

  if (month.length < 2) {
    month = '0' + month;
  }

  if (day.length < 2) {
    day = '0' + day;
  }

  return [year, month, day].join('-');
}

export const getShortDayName = (date) => {
  return new Date(date).toLocaleString('default', { weekday: 'short' });
}
