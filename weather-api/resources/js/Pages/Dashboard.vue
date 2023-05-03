<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage  } from '@inertiajs/vue3';
import jsonpAdapter from 'axios-jsonp'
import axios from 'axios';

const cors = 'https://cors-anywhere.herokuapp.com/'
const url = `http://api.openweathermap.org/data/2.5/weather`
const weekUrl = `http://api.openweathermap.org/data/2.5/forecast/daily`

export default {

        data() {
        return {
            weathers: {},
            currentTime: new Date()
        }
    },
    computed: {
        time() {
            const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
            return this.currentTime.toLocaleTimeString('en-US', options);
        }
    },
    mounted() {
        this.getWeatherData('Jakarta, ID');
         setInterval(() => {
            this.currentTime = new Date();
        }, 1000);
    },
    created() {
        this.getWeatherData('Jakarta, ID');
        this.getWeatherInWeeks('Jakarta,ID');

    },
    methods: {
        getWeatherData(city) {

            const adapter = jsonpAdapter
            axios.get(url, {
                adapter,
                params: {
                    q: city,
                    appid: 'f90dede164d3b3e721cec55c4f2fe029',

                    units: 'metric'
                }
            })
                .then(response => {
                    // console.log(response.data.main.temp);
                    this.weathers = response.data
                })
                .catch(error => {
                    console.error(error);
                });
        },
        getWeatherInWeeks(city){
            const adapter = jsonpAdapter
            // const weekUrl = `https://api.openweathermap.org/data/2.5/forecast/daily`

           axios.get(weekUrl, {
                adapter,
                params: {
                    q: city,
                    appid: 'f90dede164d3b3e721cec55c4f2fe029',
                    cnt: 7,
                    units: 'metric'
                }
            })
                .then((response) => {
                    console.log(response.data);
                    // this.weathers = response.data.list
                })
                .catch((error) => {
                    console.error(error)
                })
        },
        icon(icon){
             return `http://openweathermap.org/img/wn/${icon}@2x.png`;

        },
        toCelcius(temp){
            return Math.round(temp - 273.15);
        },
        redirectToProfile() {
            this.$inertia.visit('/profile');
        }
    }
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>


        <div class="text-white mb-8 flex flex-col justify-center items-center bg-blue-400 h-screen">
            <button @click="redirectToProfile" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-auto">
                Go to profile
            </button>
            <div class="places-input text-gray-800">
          <input type="search" id="address" class="form-control" placeholder="Search a city..." @change="getWeatherData($event.target.value)" />

          <!-- <p>Selected: <strong id="address-value">none</strong></p> -->
        </div>
        <div class="weather-container font-sans md:w-128 max-w-lg rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-8">
          <div class="current-weather flex items-center justify-between px-6 py-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="flex">
                </div>
                <div>
                  <h1 class="text-red-300">{{ time }}</h1>
                <div class="text-6xl font-semibold">{{ Math.round(weathers.main.temp) }}°C</div>
                <div>Feels like {{ Math.round(weathers.main.feels_like) }}°C</div>
              </div>
              <div class="md:mx-5">
                <div class="font-semibold">{{weathers.weather[0].main}}</div>
                <div>{{weathers.name}}</div>
              </div>
            </div>
            <div>
                 <!-- <weather-icon icon="day-sunny" /> -->
                 <!-- <h1 >{{ icon(weathers.weather[0].icon) }}</h1> -->
                 <img :src="icon(weathers.weather[0].icon)" alt="icon">
              <canvas ref="iconCurrent" id="iconCurrent" width="96" height="96"></canvas>
            </div>
          </div> <!-- end current-weather -->

          <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
            <!-- <div
              v-for="(day, index) in dailyFiveDays"
              :key="day.time"
              class="flex items-center"
              :class="{ 'mt-8': index > 0 }"
            >
        </div> -->

          </div> <!-- end future-weather -->

        </div>
        <!-- end weather-container -->
      </div>
    </AuthenticatedLayout>
</template>
