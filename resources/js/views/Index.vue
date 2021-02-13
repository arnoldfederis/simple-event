<template>
  <div>
    <b-card header="Calendar">
      <b-row>
        <b-col lg="6">
          <form @keydown="form.errors.clear($event.target.name)">
            <b-form-group label="Event" label-for="name">
              <b-form-input
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  id="name"
                  v-model="form.name"
                  type="text"
                  placeholder="Event name"
              ></b-form-input>
              <div class="invalid-feedback" v-if="form.errors.has('name')">
                {{ form.errors.get('name') }}
              </div>
            </b-form-group>

            <b-row>
              <b-col lg="6">
                <b-form-group label="From" label-for="from">
                  <b-form-datepicker :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                     id="from"
                                     v-model="form.from"
                                     class="mb-2"
                                     :class="{ 'is-invalid': form.errors.has('from') }"
                                     @shown="clearErrors"
                                     placeholder="MM/DD/YYYY"></b-form-datepicker>
                  <div class="invalid-feedback" v-if="form.errors.has('from')">
                    {{ form.errors.get('from') }}
                  </div>
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <b-form-group label="To" label-for="to">
                  <b-form-datepicker :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                     id="to"
                                     v-model="form.to"
                                     class="mb-2"
                                     :class="{ 'is-invalid': form.errors.has('to') }"
                                     @shown="clearErrors"
                                     :min="minDate"
                                     placeholder="MM/DD/YYYY"></b-form-datepicker>
                  <div class="invalid-feedback" v-if="form.errors.has('to')">
                    {{ form.errors.get('to') }}
                  </div>
                </b-form-group>
              </b-col>
            </b-row>

            <div class="mb-3">
              <b-form-group>
                <b-form-checkbox-group v-model="form.days" name="days" class="checkbox-group d-lg-flex justify-content-lg-between">
                  <b-form-checkbox
                      v-for="(day, index) in days"
                      :key="index"
                      :value="day.value"
                      :disabled="!day.enable"
                      @change="clearErrors"
                  >
                    <span class="text-capitalize">{{ day.value }}</span>
                  </b-form-checkbox>
                </b-form-checkbox-group>
                <div class="invalid-feedback" :class="{ 'd-block': form.errors.has('days') }" v-if="form.errors.has('days')">
                  {{ form.errors.get('days') }}
                </div>
              </b-form-group>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary btn-block" type="button" @click="saveEvent" :disabled="submitting">
                <span v-if="submitting">Saving</span>
                <span v-else>Save</span>
              </button>
            </div>
          </form>
        </b-col>

        <b-col lg="6">
          <calendar ref="calendar" :date="{ from: form.from, to: form.to, has_return_data: hasReturnData, days: daysData }" />
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import { getDaysArray, getShortDayName } from '../helpers';

export default {
  data() {
    return {
      form: new Form({
        name: null,
        from: null,
        to: null,
        days: []
      }),
      submitting: false,
      days: [
        {value: 'mon', enable: true},
        {value: 'tue', enable: true},
        {value: 'wed', enable: true},
        {value: 'thu', enable: true},
        {value: 'fri', enable: true},
        {value: 'sat', enable: true},
        {value: 'sun', enable: true}
      ],
      minDate: null,
      hasReturnData: false,
      daysData: []
    }
  },

  methods: {
    saveEvent() {
      this.submitting = true;

      this.form.post('api/events')
      .then((response) => {
        this.hasReturnData = true;

        this.daysData = _.map(response.days, (day) => {
          return {date: day.date, name: response.name, from: response.from, to: response.to};
        });

        this.$root.$emit('alert', {show: true})
        setTimeout(() => this.submitting = false, 500);
      })
      .catch(() => this.submitting = false);
    },

    clearErrors() {
      return this.form.errors.clearAll()
    },

    watchForm(newVal) {
      this.minDate = new Date(newVal.from)
      if (newVal.from > newVal.to) {
        this.form.to = null;
      }

      if (newVal.from && newVal.to) {
        _.map(this.days, (day) => day.enable = false);

        let dates = _.map(getDaysArray(newVal.from, newVal.to), (date) => {
          return getShortDayName(date.identifier);
        });

        _.uniq(dates).filter((date) => {
          _.filter(this.days, {value: date.toLowerCase()}).map((day) => day.enable = true);
        });
      }
    }
  },

  watch: {
    form: {
      handler: 'watchForm',
      deep: true
    }
  }
}
</script>

<style lang="scss">
  .b-calendar-grid {
     &:focus {
       border-color: #ced4da !important;
       box-shadow: none !important;
     }
   }

  .form-group {
    label {
      user-select: none;
    }
  }

  .checkbox-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    .custom-control {
      user-select: none;
      &:last-child {
        margin-right: 0 !important;
      }
    }
  }
</style>
