// this file is same like router , its being used for VUEX

import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state : {
        counter: 10,
        deleteModalObj : {
            showDeleteModal: false,
            deleteUrl : '',
            data : null,
            deletingIndex: -1,
            isDeleted : false,
            successMsg: '',
            msg:''

        },
        user: false,
        userPermission : null,
    },

    // common getters
    getters: {
        getCounter(state)
        {
          return state.counter;
        },

        getDeleteModalObj(state){
            return state.deleteModalObj
        },

        getUserPermission(state){
            return state.userPermission
        },
    },

    // mutations for changing data from action
    mutations: {
        changeTheCounter(state, data){
            state.counter += data
        },
        setDeleteModal(state, data){
            const deleteModalObj = {
                showDeleteModal: false,
                deleteUrl : '',
                data : null,
                deletingIndex: -1,
                isDeleted : data,
            }
            state.deleteModalObj = deleteModalObj
        },
        setDeletingModalObj(state, data){
            state.deleteModalObj = data
        },

        setUpdateUser(state, data){
            state.user = data
        },
        setUserPermission(state, data){
            state.userPermission = data
        },



    },

    // action for commiting mutations
    actions :{
        changeCounterAction({commit},data){
            commit('changeTheCounter',data)
        }
    }

})