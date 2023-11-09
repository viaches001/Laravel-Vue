<template>
    <div class="lg:flex">
        <!-- LapTop -->
        <div class="print:hidden flex-shrink hidden lg:flex min-h-screen">
            <!-- Primary Navigation Menu -->
            <div class="flex-grow lg:flex-shrink sticky top-0 h-screen">
                <div class="w-full lg:w-96">
                    <!-- Expand/Collapse Button -->
                    <transition name="slide">
                        <div class="flex flex-col shadow-xl sm:rounded-lg h-screen" :class="{'w-96': is_expand, 'w-24': !is_expand}">
                            <div>
                                <button @click="is_expand = !is_expand" class="float-right pt-2 pb-2 px-8 border-none w-24" >
                                    <div v-if="is_expand"><img src='/images/collapse.png' class='w-8 h-8'/></div>
                                    <div v-else><img src='/images/expand.png' class='w-8 h-8' /></div>
                                </button>
                            </div>
                            <!-- Logo -->
                            <div :class="{ invisible: !is_expand }" class="w-80 md:w-96 h-20 md:h-20 px-8 md:px-6 lg:px-8 pt-2 md:pt-2 lg:pt-2 pb-2 lg:pb-2 flex items-center mb-2" >
                                <inertia-link :href="route('workorders.workorders')">
                                    <jet-application-mark class=" h-auto w-auto" />
                                </inertia-link>
                            </div>

                            <!-- Admin Navigation Links -->
                            <transition name="slide">
                                <template v-if="$page.user.role === 'admin'">
                                    <div>
                                        <jet-nav-link 
                                            v-for="(item, index) in nav_items"
                                            v-bind:style="{ 
                                                paddingLeft: '5px', 
                                                paddingRight: '5px',
                                                color: 'black',
                                                borderRadius: '10px',
                                                marginLeft: '10%',
                                                marginRight: '10%',
                                                marginTop: '3px',
                                                marginBottom: '3px',
                                                width: '80%',
                                                height: '80px',
                                                fontSize: '25px',
                                            }" 
                                            :href="route(item.href)" 
                                            :active="route().current(item.current) && active_subnav === null"
                                        >
                                            <div 
                                                class="w-16 h-16" 
                                                v-bind:style="{ 
                                                    backgroundImage: 'url(/images/ellipse.png)', 
                                                    backgroundSize: 'cover', 
                                                    paddingRight: '10px'
                                                }"
                                                v-if="!is_expand"
                                            >
                                                <img class="w-10 h-10 m-3" v-bind:src="item.icon"/>
                                            </div>
                                            <div 
                                                class="w-10 h-10 mr-9 ml-3 mt-10 mb-10" 
                                                v-if="is_expand"
                                            >
                                                <img v-bind:src="item.icon"/>
                                            </div>
                                            <span v-if="is_expand" v-bind:style="{ lineHeight: '130%'}">{{ item.name }}</span>
                                        </jet-nav-link>
                                        <div class="absolute bottom-0" :class="{'w-96': is_expand, 'w-24': !is_expand}">
                                            <div class="float-right w-48 bg-gray-300 mr-10 rounded-2xl" v-if="is_expand && active_subnav == 'Account'">
                                                <jet-nav-link 
                                                    v-bind:style="{ 
                                                        paddingLeft: '5px', 
                                                        paddingRight: '5px',
                                                        color: 'black',
                                                        borderRadius: '10px',
                                                        marginLeft: '10%',
                                                        marginRight: '10%',
                                                        marginTop: '12px',
                                                        marginBottom: '12px',
                                                        width: '80%',
                                                        height: '40px',
                                                        fontSize: '22px',
                                                    }" 
                                                    :href="route('profile.show')" 
                                                    :active="route().current('profile.show')"
                                                >
                                                    <div 
                                                        class="w-16 h-16" 
                                                        v-bind:style="{ 
                                                            backgroundImage: 'url(/images/ellipse.png)', 
                                                            backgroundSize: 'cover', 
                                                            paddingRight: '10px'
                                                        }"
                                                        v-if="!is_expand"
                                                    >
                                                        <img class="w-10 h-10 m-3" src="/images/profile.png"/>
                                                    </div>
                                                    <div 
                                                        class="w-5 h-5 mr-4 ml-3 mt-2 mb-2" 
                                                        v-if="is_expand"
                                                    >
                                                        <img src="/images/profile.png"/>
                                                    </div>
                                                    <div><span>Profile</span></div>
                                                </jet-nav-link>
                                                <!-- Authentication -->
                                                <form method="POST" @submit.prevent="logout">
                                                    <button type="submit" 
                                                        v-bind:style="{ 
                                                            paddingLeft: '5px', 
                                                            paddingRight: '5px',
                                                            color: 'black',
                                                            borderRadius: '10px',
                                                            marginLeft: '10%',
                                                            marginRight: '10%',
                                                            marginTop: '12px',
                                                            marginBottom: '12px',
                                                            width: '80%',
                                                            height: '40px',
                                                            fontSize: '22px',
                                                        }" 
                                                        class="inline-flex items-center w-full h-12 px-8 text-lg font-medium leading-5 text-gray-200 bg-transparent hover:text-white hover:bg-gray-100 hover:bg-opacity-25 focus:outline-none focus:text-white focus:bg-gray-20 focus:bg-opacity-25 transition duration-150 ease-in-out">
                                                        <div 
                                                            class="w-16 h-16" 
                                                            v-bind:style="{ 
                                                                backgroundImage: 'url(/images/ellipse.png)', 
                                                                backgroundSize: 'cover', 
                                                                paddingRight: '10px'
                                                            }"
                                                            v-if="!is_expand"
                                                        >
                                                            <img class="w-10 h-10 m-3" src="/images/logout.png"/>
                                                        </div>
                                                        <div 
                                                            class="w-5 h-5 mr-4 ml-3 mt-2 mb-2" 
                                                            v-if="is_expand"
                                                        >
                                                            <img src="/images/logout.png"/>
                                                        </div>
                                                        <div><span>Logout</span></div>
                                                    </button>
                                                </form>
                                            </div>
                                            <div>
                                                <jet-nav-link 
                                                    v-bind:style="{ 
                                                        paddingLeft: '5px', 
                                                        paddingRight: '5px',
                                                        color: 'black',
                                                        borderRadius: '10px',
                                                        marginLeft: '10%',
                                                        marginRight: '10%',
                                                        marginTop: '12px',
                                                        marginBottom: '12px',
                                                        width: '80%',
                                                        height: '80px',
                                                        fontSize: '25px',
                                                    }" 
                                                    :subnav_toggle="true"
                                                    @click.native="update_subnav('Account')"
                                                    :active="(route().current('profile.*') && active_subnav === null) || active_subnav === 'Account'"
                                                >
                                                    <div 
                                                        class="w-16 h-16" 
                                                        v-bind:style="{ 
                                                            backgroundImage: 'url(/images/ellipse.png)', 
                                                            backgroundSize: 'cover', 
                                                            paddingRight: '10px'
                                                        }"
                                                        v-if="!is_expand"
                                                    >
                                                        <img class="w-10 h-10 m-3" src="/images/user.png"/>
                                                    </div>
                                                    <div 
                                                        class="w-10 h-10 mr-9 ml-3 mt-10 mb-10" 
                                                        v-if="is_expand"
                                                    >
                                                        <img src="/images/user.png"/>
                                                    </div>
                                                    <div><span v-if="is_expand" 
                                                        v-bind:style="{ 
                                                            lineHeight: '130%', 
                                                            fontSize: '25px', 
                                                            color: 'black',
                                                            marginTop: '12px',
                                                            marginBottom: '12px',
                                                        }"
                                                    >{{ $page.user.name }}</span></div>
                                                </jet-nav-link>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <jet-nav-link :active="(route().current('settings.*') && active_subnav === null) || active_subnav === 'Settings'" :subnav_toggle="true" @click.native="update_subnav('Settings')">
                                        Settings
                                    </jet-nav-link> -->
                                </template>
                             </transition>

                            <!-- Installers Navigation Links -->
                            <template v-if="$page.user.role === 'installer'">
                                <template v-if="!$page.user.service_calls">
                                </template>
                                <template v-else>
                                </template>
                            </template>
                        </div>
                    </transition>
                </div>
            </div>

           
        </div>
        <!-- Mobile -->
        <div class="print:hidden lg:hidden fixed top:0 left:0 min-h-screen z-50">
            <div class="flex bg-white animate__animated animate__faster" :class="{'hidden animate__slideOutLeft': !active_nav, 'animate__slideInLeft': active_nav}">
                <!-- Primary Navigation Menu -->
                <div class="flex-shrink sticky top-0 h-screen">
                    <div class="w-40 md:w-48">
                        <!-- Logo -->
                        <div class="w-40 md:w-48 h-40 md:h-48 px-4 md:px-6 lg:px-8 pt-4 md:pt-6 lg:pt-8 pb-4 lg:pb-8 flex items-center">
                            <inertia-link :href="route('workorders.workorders')">
                                <jet-application-mark class="h-auto w-auto" />
                            </inertia-link>
                        </div>

                        <!-- Admin Navigation Links -->
                        <template v-if="$page.user.role === 'admin'">
                            <jet-nav-link :href="route('customers.customers')" :active="route().current('customers.*') && active_subnav === null">
                                Customers
                            </jet-nav-link>
                            <jet-nav-link :href="route('readings.readings')" :active="route().current('readings.*') && active_subnav === null">
                                Readings
                            </jet-nav-link>
                            <jet-nav-link :href="route('workorders.workorders')" :active="route().current('workorders.*') && active_subnav === null">
                                Workorders
                            </jet-nav-link>
                            <jet-nav-link :href="route('reports.reports')" :active="route().current('reports.*') && active_subnav === null">
                                Reported Issues
                            </jet-nav-link>
                            <jet-nav-link :href="route('notifications.notifications')" :active="route().current('notifications.*') && active_subnav === null">
                                Notifications
                            </jet-nav-link>
                            <jet-nav-link :href="route('users.users')" :active="route().current('users.*') && active_subnav === null">
                                Users
                            </jet-nav-link>
                            <!-- <jet-nav-link :active="(route().current('settings.*') && active_subnav === null) || active_subnav === 'Settings'" :subnav_toggle="true" @click.native="update_subnav('Settings')">
                                Settings
                            </jet-nav-link> -->
                        </template>

                        <!-- Installers Navigation Links -->
                        <template v-if="$page.user.role === 'installer'">
                            <template v-if="!$page.user.service_calls">
                            </template>
                            <template v-else>
                            </template>
                        </template>

                        <jet-nav-link :active="(route().current('profile.*') && active_subnav === null) || active_subnav === 'Account'" :subnav_toggle="true" @click.native="update_subnav('Account')">
                            {{ $page.user.name }}
                        </jet-nav-link>
                    </div>
                </div>

                <!-- Secondary Navigation -->
                <div class="flex-shrink sticky top-0 h-screen bg-brandcolour">
                    <div :class="{ 'w-4': active_subnav === null, 'w-40 md:w-48': active_subnav !== null}"
                        style="
                            -webkit-transition: width 0.25s ease-in-out;
                            -moz-transition: width 0.25s ease-in-out;
                            -o-transition: width 0.25s ease-in-out;
                            transition: width 0.25s ease-in-out;"
                    >
                        <div class="animate__animated animate__fadeIn animate__delay-1s animate__faster" v-if="active_subnav !== null">
                            <!-- Header -->
                            <div class="w-40 md:w-48 h-40 md:h-48 px-4 md:px-6 lg:px-8 pt-4 pd:mt-6 lg:pt-8 pb-4 lg:pb-8 text-xl md:text-3xl text-white font-bold flex items-center">
                                <div>
                                    {{ active_subnav }}
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <!-- Settings -->
                            <!-- <div v-if="active_subnav === 'Settings'">
                                <template v-if="$page.user.role === 'admin'">
                                    <jet-sub-nav-link :href="route('settings.items')" :active="route().current('settings.items')">
                                        Materials
                                    </jet-sub-nav-link>
                                    <jet-sub-nav-link :href="route('settings.job_types')" :active="route().current('settings.job_types')">
                                        Job Types
                                    </jet-sub-nav-link>
                                </template>
                            </div> -->
                            <!-- Account -->
                            <div v-if="active_subnav === 'Account'">
                                <jet-sub-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profile
                                </jet-sub-nav-link>
                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <button type="submit" class="inline-flex items-center w-full h-12 px-4 md:px-6 lg:px-8 text-base md:text-lg font-medium leading-5 text-gray-200 bg-transparent hover:text-white hover:bg-gray-100 hover:bg-opacity-25 focus:outline-none focus:text-white focus:bg-gray-100 focus:bg-opacity-25 transition duration-150 ease-in-out">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="print:hidden md:hidden fixed top-0 right-0 min-h-screen z-50" v-if="has_filters">
            <div class="flex bg-white animate__animated animate__faster" :class="{'hidden animate__slideOutRight': !active_filters, 'animate__slideInRight': active_filters}">
                <!-- Colour Bar -->
                <div class="flex-shrink sticky top-0 h-screen bg-brandcolour">
                    <div class="w-4"></div>
                </div>

                <!-- Filters -->
                <div class="flex-shrink sticky top-0 h-screen">
                    <div class="w-60 md:w-68">
                        <!-- Header -->
                        <div class="w-60 md:w-68 h-40 md:h-48 px-4 md:px-6 lg:px-8 pt-4 pd:mt-6 lg:pt-8 pb-4 lg:pb-8 text-3xl md:text-4xl font-bold flex items-center">
                            <div>
                                Filters
                            </div>
                        </div>

                        <div class="px-4 md:px-6 lg:px-8">
                            <slot name="filters"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:flex-grow min-h-screen relative">
            <div @click="active_nav = false; active_filters = false;" class="absolute left-0 top-0 bg-black opacity-50 h-full w-full z-40" :class="{'hidden': !active_nav && !active_filters}"></div>

            <!-- Offline Bar -->
            <div class="print:hidden sticky top-0 px-4 md:px-6 lg:px-8 pt-2 md:pt-4 lg:pt-6 z-50">
                <jet-alert-message class_type="danger" class="z-50" v-if="!online">
                    <wifi-alert-icon></wifi-alert-icon>&nbsp;&nbsp;No Network Connection!
                </jet-alert-message>
            </div>

            <!-- Page Heading -->
            <header class="flex items-center px-4 md:px-6 lg:px-8 pt-4 lg:pt-6" :class="{'print:hidden': route().current('reports.*')}">
                <div class="flex-grow">
                    <h2 class="font-bold text-3xl md:text-4xl lg:text-5xl text-gray-800 leading-none">
                        <slot name="header"></slot>
                    </h2>
                </div>
                <div class="flex-shrink">
                    <slot name="header_buttons"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main class="px-4 md:px-6 lg:px-8 pt-2 md:pt-4 lg:pt-6 pb-16 md:pb-0">
                <slot></slot>
            </main>

            <!-- Page Footer Left -->
            <div class="print:hidden lg:hidden fixed bottom-0 left-0 mx-4 md:mx-6 lg:mx-8 my-4 md:my-6 z-50">
                <div class="inline-block animate__animated animate__faster" :class="{'hidden animate__fadeOutLeft': active_filters, 'animate__fadeInLeft': active_filters}">
                    <jet-button shape="circle" class="p-4" @click.native="active_nav = !active_nav" :class="{'hidden': active_nav}"><menu-icon></menu-icon></jet-button>
                    <jet-button shape="circle" class="p-4" @click.native="active_nav = !active_nav" :class="{'hidden': !active_nav}"><close-icon></close-icon></jet-button>
                </div>
                <slot name="footer_buttons_left"></slot>
            </div>

            <!-- Page Footer Right -->
            <div class="print:hidden lg:hidden fixed bottom-0 right-0 mx-4 md:mx-6 lg:mx-8 my-4 md:my-6 z-50">
                <div v-if="has_filters" class="inline-block animate__animated animate__faster" :class="{'hidden animate__fadeOutRight': active_nav, 'animate__fadeInRight': active_nav}">
                    <jet-button shape="circle" class="p-4" @click.native="active_filters = !active_filters" :class="{'hidden': active_filters}"><filter-icon></filter-icon></jet-button>
                    <jet-button shape="circle" class="p-4" @click.native="active_filters = !active_filters" :class="{'hidden': !active_filters}"><close-icon></close-icon></jet-button>
                </div>
                <slot name="footer_buttons_right"></slot>
            </div>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple>
            </portal-target>
        </div>
    </div>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetSubNavLink from '@/Jetstream/SubNavLink'
    import JetButton from '@/Jetstream/Button';
    import JetAlertMessage from '@/Jetstream/AlertMessage';

    import { mapState } from 'vuex';

    export default {
        components: {
            JetApplicationMark,
            JetNavLink,
            JetSubNavLink,
            JetButton,
            JetAlertMessage,
        },

        data() {
            return {
                active_nav: false,
                active_subnav: null,
                active_filters: false,
                is_expand: true,
                nav_items: [
                    {
                        href: 'customers.customers',
                        icon: '/images/customers.png',
                        current: 'customers.*',
                        name: 'Customers'
                    },
                    {
                        href: 'readings.readings',
                        icon: '/images/readings.png',
                        current: 'readings.*',
                        name: 'Readings'
                    },
                    {
                        href: 'workorders.workorders',
                        icon: '/images/workorders.png',
                        current: 'workorders.*',
                        name: 'Workorders'
                    },
                    {
                        href: 'reports.reports',
                        icon: '/images/reports.png',
                        current: 'reports.*',
                        name: 'Reported Issues'
                    },
                    {
                        href: 'notifications.notifications',
                        icon: '/images/notifications.png',
                        current: 'notifications.*',
                        name: 'Notification'
                    },
                    {
                        href: 'users.users',
                        icon: '/images/users.png',
                        current: 'users.*',
                        name: 'Users'
                    },
                ]
            }
        },

        mounted () {
            window.addEventListener('keydown', function(e) {
                if ( e.keyIdentifier=='U+000A' || e.keyIdentifier =='Enter' || e.keyCode==13)
                {
                    if (e.target.nodeName !== 'TEXTAREA' && e.target.nodeName !== 'BUTTON' && !e.target.classList.contains('vs__search'))
                    {
                        e.preventDefault();
                        e.target.blur();
                        return false;
                    }
                }
            },true);
        },

        methods: {
            update_subnav(new_subnav)
            {
                if (this.active_subnav == new_subnav) this.active_subnav = null;
                else this.active_subnav = new_subnav;
            },

            logout() {
                axios.post(route('logout').url()).then(response => {
                    window.location = '/';
                })
            },
        },

        watch: {
            active_nav () {
                if (this.active_nav) this.active_subnav = null;
            },
        },

        computed: {
            ... mapState({
                online: state => state.online,
            }),

            has_filters () {
                return !!this.$slots.filters;
            }
        }
    }
</script>

<style>
    .slide-enter-active, .slide-leave-active {
        transition: width 3s;
    }
    .slide-enter, .slide-leave-to{
        width: 50px;
    }
    @keyframes slide {
        0%   {width: 50px;}
        50%   {width: 150px;}
        100% {width: 300px; }
    }
</style>