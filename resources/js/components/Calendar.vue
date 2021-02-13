<template>
  <div>
    <h1>{{ dateName }}</h1>
    <div class="listing-group-date">
      <div class="listing-date-content">
        <b-list-group-item v-for="(dateObj, index) in dates" :key="index" :variant="dateObj.name ? 'success' : ''">
          <b-row>
            <b-col lg="3">
              {{ formatListingDate(dateObj.date) }}
            </b-col>
            <b-col lg="9">
              {{ dateObj.name }}
            </b-col>
          </b-row>
        </b-list-group-item>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    date: {
      type: Object,
      required: false
    }
  },

  data() {
    return {
      dateName: null,
      dates: [],
      days: [],
      monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    }
  },

  mounted() {
    let currentDate = new Date();
    const plusOneMonth = currentDate.setMonth(currentDate.getMonth() + 1);
    this.dates = this.getDaysArray(new Date(), plusOneMonth);
    this.getDefaultDateName();
  },

  methods: {
    watchDate(newVal) {
      if (newVal.from && newVal.to) {
        console.log('hello')
        this.dates = this.getDaysArray(newVal.from, newVal.to);
        this.dateName = this.getDateName(newVal);
      }
    },

    watchDays(days) {
      if (days) {
        let clonedDates = _.clone(this.dates);

        days.forEach(day => {
          _.filter(clonedDates, {identifier: this.getShortDayName(day.date).toLowerCase()}).map((date) => date.name = day.name)
        })

        this.dates = clonedDates;
      }
    },

    getDaysArray(startDate, endDate) {
      let dates = [];
      let currentDate = new Date(startDate);

      while (currentDate <= new Date(endDate)) {
        dates.push({
          name: null,
          date: new Date(currentDate),
          identifier: this.getShortDayName(currentDate).toLowerCase(),
          change_date_name: new Date(currentDate).getDate() === 1
        });

        currentDate.setDate(currentDate.getDate() + 1);
      }

      return dates;
    },

    getDateName(date) {
      let dateName;
      let fromDate = new Date(date.from);
      let toDate = new Date(date.to);

      let fromMonth = this.monthNames[fromDate.getMonth()];
      let toMonth = this.monthNames[toDate.getMonth()];
      let fromYear = fromDate.getFullYear();
      let toYear = toDate.getFullYear();

      if (fromMonth === toMonth && fromYear === toYear) {
        dateName = `${fromMonth} ${fromYear}`;
      } else if (fromMonth !== toMonth && fromYear === toYear) {
        dateName = `${fromMonth} - ${toMonth} ${toYear}`;
      } else {
        dateName = `${fromMonth} ${fromYear} - ${toMonth} ${toYear}`;
      }

      return dateName;
    },

    getDefaultDateName() {
      let dateCoverLength = _.filter(this.dates, {change_date_name: true}).length;
      let currentDate = new Date();

      if (dateCoverLength > 0) {
        this.dateName = `${this.monthNames[currentDate.getMonth()]} - ${this.monthNames[currentDate.getMonth() + dateCoverLength]} ${currentDate.getFullYear()}`;
      } else {
        this.dateName = this.monthNames[currentDate.getMonth()];
      }
    },

    getShortDayName(date) {
      return new Date(date).toLocaleString('default', { weekday: 'short' });
    },

    formatListingDate(date) {
      return `${date.getDate()} ${this.getShortDayName(date)}`;
    }
  },

  watch: {
    date: 'watchDate',
    days: 'watchDays'
  }
}
</script>

<style lang="scss" scoped>
  .listing-group-date {
    .listing-date-content {
      max-height: 500px;
      overflow-y: auto;
    }

    .list-group-item {
      border-right-width: 0;
      border-left-width: 0;
    }
  }
</style>

