const state = {
    calendar: {
        event_name: null,
        date_from: null,
        date_to: null,
        days: []
    }
};

const getters = {
    allEvents: (state) => state.calendar
};

const mutations = {
    UPDATE_CALENDAR_VALUES(state, value) {
        state.calendar.date_from = value.date_from;
        state.calendar.date_to = value.date_to;
        state.calendar.event_name = value.event_name;
        state.calendar.days = value.days;
    }
};

const actions = {
    setCalendarValues: ({commit}, value) => {
        commit('UPDATE_CALENDAR_VALUES', value);
    },
};

export default {
    state,
    getters,
    mutations,
    actions
}
