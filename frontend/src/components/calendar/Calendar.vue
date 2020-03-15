<template>
  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar flat color="white">
          <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
            Today
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="prev">
            <v-icon small>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="next">
            <v-icon small>mdi-chevron-right</v-icon>
          </v-btn>
          <v-toolbar-title> {{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu bottom right>
            <template v-slot:activator="{ on }">
              <v-btn
                outlined
                color="grey darken-2"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>mdi-menu-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="550">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="getEvents"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="350px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-btn icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon>
                <v-icon>mdi-heart</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <span v-html="selectedEvent.details"></span>
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="selectedOpen = false"
              >
                Cancel
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
  </v-row>
</template>

<script>

    import Moment from 'moment';
    import {extendMoment} from 'moment-range';

    const moment = extendMoment(Moment);
    import {mapState} from 'vuex';

    export default {
        data: () => ({
            focus: '',
            type: 'month',
            typeToLabel: {
                month: 'Month',
                week: 'Week',
                day: 'Day',
                '4day': '4 Days',
            },
            start: null,
            end: null,
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
            colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        }),
        computed: {

            ...mapState({
                calendar: state => state.calendar
            }),

            title() {
                const {start, end} = this;
                if (!start || !end) {
                    return ''
                }

                const startMonth = this.monthFormatter(start);
                const endMonth = this.monthFormatter(end);
                const suffixMonth = startMonth === endMonth ? '' : endMonth;

                const startYear = start.year;
                const endYear = end.year;
                const suffixYear = startYear === endYear ? '' : endYear;
                console.log(startMonth);

                const startDay = start.day + this.nth(start.day);
                const endDay = end.day + this.nth(end.day);

                switch (this.type) {
                    case 'month':
                        return `${startMonth} ${startYear}`;
                    case 'week':
                    case '4day':
                        return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`;
                    case 'day':
                        return `${startMonth} ${startDay} ${startYear}`
                }
                return ''
            },
            monthFormatter() {
                return this.$refs.calendar.getFormatter({
                    timeZone: 'UTC', month: 'long',
                })
            },

            getEvents() {
                const events = [];
                let fromdate = moment(this.$store.state.events.calendar.date_from).format('YYYY-MM-DD');
                let todate = moment(this.$store.state.events.calendar.date_to).format('YYYY-MM-DD');

                let range = moment.range(fromdate, todate);

                for (let dts of range.by('days')) {
                    let compare_dt = dts.format('YYYY-MM-DD');
                    let days = this.$store.state.events.calendar.days.map(Number);
                    let momentDate = moment(compare_dt);
                    days.forEach((day) => {
                        if (momentDate.day() === day) {
                            events.push({
                                name: this.$store.state.events.calendar.event_name,
                                start: this.formatDate(compare_dt, false),
                                end: this.formatDate(compare_dt, false),
                                color: this.colors[this.rnd(0, this.colors.length - 1)],
                            });
                        }
                    });
                }
                return events;
            }
        },
        mounted() {
            this.$refs.calendar.checkChange()
        },
        methods: {
            viewDay({date}) {
                this.focus = date;
                this.type = 'day'
            },
            getEventColor(event) {
                return event.color
            },
            setToday() {
                this.focus = this.today
            },
            prev() {
                this.$refs.calendar.prev()
            },
            next() {
                this.$refs.calendar.next()
            },
            showEvent({nativeEvent, event}) {
                const open = () => {
                    this.selectedEvent = event;
                    this.selectedElement = nativeEvent.target;
                    setTimeout(() => this.selectedOpen = true, 10)
                };

                if (this.selectedOpen) {
                    this.selectedOpen = false;
                    setTimeout(open, 10)
                } else {
                    open()
                }

                nativeEvent.stopPropagation()
            },

            updateRange({start, end}) {
                const events = [];
                this.start = start;
                this.end = end;
                this.events = events;
            },

            nth(d) {
                return d > 3 && d < 21
                    ? 'th'
                    : ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][d % 10]
            },
            rnd(a, b) {
                return Math.floor((b - a + 1) * Math.random()) + a
            },
            formatDate(a, withTime) {
                const date = new Date(a);
                return withTime
                    ? `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}}`
                    : `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`
            },
        },
    }
</script>
