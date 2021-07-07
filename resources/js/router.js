import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import home from './components/pages/home'
import usecom from './vuex/usecom'

//import tags from './components/pages/tag'
// admin user 
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'
import blogs from './admin/pages/blogs'
import editblog from './admin/pages/editblog'
import notfound from './admin/pages/notfound'




const routes = [{
        path: '/testvuex',
        component: usecom
    },

    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'

    },
    {
        path: '/createBlog',
        component: createBlog,
        name: 'createBlog'

    },
    {
        path: '/blogs',
        component: blogs,
        name: 'blogs'

    },

    {
        path: '/editblog/:id',
        component: editblog,
        name: 'editblog'

    },
    {
        path: '*',
        component: notfound,
        name: 'notfound'

    },
    {
        path: '/adminusers',
        component: adminusers,
        name: 'adminusers'

    },
    {
        path: '/login',
        component: login,
        name: 'login'

    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole'
    },



]

export default new Router({
    mode: 'history',
    routes
})