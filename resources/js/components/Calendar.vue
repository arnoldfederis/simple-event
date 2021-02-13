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
import { formatDate, getDaysArray, getShortDayName } from '../helpers';

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
      monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    }
  },

  mounted() {
    let currentDate = new Date();
    const plusOneMonth = currentDate.setMonth(currentDate.getMonth() + 1);
    this.dates = getDaysArray(new Date(), plusOneMonth);
    this.getDefaultDateName();
  },

  methods: {
    watchDateProps(newVal) {
      if (newVal.from && newVal.to) {
        if (newVal.has_return_data) {
          this.dates = getDaysArray(newVal.from, newVal.to);

          _.map(this.dates, (date) => date.name = null);
          newVal.days.forEach(day => {
            let identifier = formatDate(day.date);
            _.filter(this.dates, {identifier}).map((date) => date.name = day.name)

            let newItem = {
              name: day.name,
              date: new Date(day.date),
              change_date_name: new Date(day.date).getDate() === 1,
              identifier
            }

            this.$set(this.dates, _.findIndex(this.dates, {identifier}), newItem);
          })
        } else {
          this.dates = getDaysArray(newVal.from, newVal.to);
        }

        this.dateName = this.getDateName(newVal);
      }
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

    formatListingDate(date) {
      return `${date.getDate()} ${getShortDayName(date)}`;
    }
  },

  watch: {
    date: 'watchDateProps'
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

