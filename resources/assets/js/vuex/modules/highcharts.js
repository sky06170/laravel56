const state = {
    categories: [],
    years: [],
    months: []
};

const getters = {
    categories: (state) => state.categories,
    years: (state) => state.years,
    months: (state) => state.months
};

const mutations = {
    setSearchBarInfo (state, data) {
        state.categories = data.categories;
        state.years = data.years;
        state.months = data.months;
    },
};

const actions = {
    initPage ({ commit }) {
        axios.post(`/api/currency/searchBarInfo`)
            .then((response) => {
                commit('setSearchBarInfo', response.data);
            })
            .catch((thrown) => {
                console.log(thrown);
            });
    }
};

export default {
    namespaced: true,
	state,
	getters,
	mutations,
	actions
}