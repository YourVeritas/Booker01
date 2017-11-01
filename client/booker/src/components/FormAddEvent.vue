<template>
<div class="form-container">
  <div class="label-input">1. Booker for:</div>  
  <div v-if="!admin">
      UserName
  </div>
  <div v-if="admin">
      <select class="select-input" id="user" v-model="user" v-on:change="">
          <option value="-">User Name</option>
          <option v-for="user in usersItems" :user="user" :value="user.value">
              {{user.title}}
          </option>
      </select>
  </div>
  <div class="label-input">2. I wood like to book this meeting:</div>
  <div>
      <select class="select-input-small" id="month" v-model="month" v-on:change="createDate()">
          <option v-for="(month, index) in monthsItems" :month="month" :value="index">
              {{month}}
          </option>
      </select>
      <select class="select-input-small" id="day" v-model="day">
          <option v-for="day in daysItems" :day="day" :value="day">
              {{day}}
          </option>
      </select>
      <select class="select-input-small" id="year" v-model="year">
          <option v-for="year in yearsItems" :year="year" :value="year">
              {{year}}
          </option>
      </select>
  </div>
  <div class="label-input">3. Specify what the time start and end of the meeting (This will be what people see on the calendar.)</div>
  <div class="label-input">4. Enter the specifics for the meeting. (This will be what people see when they click on an event link.)</div>
  <div><textarea rows="7" cols="60" name="text"></textarea></div>
  <div class="label-input">5. Is this going to be a reacurring event?</div>
    <div><input name="reacurring-flag" v-model="reacurringFlag" value="true" type="radio"> Yes</div>
    <div><input name="reacurring-flag" v-model="reacurringFlag" value="false" type="radio"> No </div>
  <div class="label-input">6. If it is recurring, specify weekly, be-weekly, or monthly.</div>
    <div><input name="reacurring-type" v-model="reacurringType"  value="weekly" type="radio"> Weekly</div>
    <div><input name="reacurring-type" v-model="reacurringType"  value="be-weekly" type="radio"> Be-weekly </div>
    <div><input name="reacurring-type" v-model="reacurringType"  value="monthly" type="radio"> Monthly </div>
  <div class="label-input">If weekly, or bi-weekly, specify the number of weeks for it ti keep recurring. If to keep recurring. If monthly, specify the number of months. (If you choose 'bi-weekly' and put in an odd number of weeks, the computer will round down)</div>
<div @click="sayHi()">Submit</div>
</div>
</template>
<script>
  import axios from 'axios'

  export default {
    props: ['items'],
  
    data() {
      return {
          admin: true,
          user: '-',
          month: '',
          day: '2',
          year: '',
          usersItems: [{title:'Maria', value: 1},{title:'Alex', value: 2},{title:'Colin', value: 3}],
          monthsItems: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'],
          daysItems: [],
          yearsItems: [],
          reacurringFlag: 'false',
          reacurringType: 'false',
      }
    },
        created() {
            var data = new Date()
            this.day = data.getDate()
            this.month = data.getMonth()
            this.year = data.getFullYear()
            this.createDate()
    },
    watch: {

    },
    computed: {
    },
    methods: {
        sayHi(){
            console.log(this.reacurringFlag)
        },
        createDate(){

            this.daysItems = []
            var days_in_month = 32 - new Date(this.year, this.month, 32).getDate()
            if(this.day > days_in_month)
                this.day = days_in_month

            for(var i=1; i<=days_in_month; i++)
            this.daysItems.push(i)

            this.yearsItems = []
            for(var i=0; i<7; i++)
                this.yearsItems.push(+this.year + i)

        },
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
*{ 
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  text-decoration: none;
  color: #FFFFFF;
}

.form-container{
width: 800px;
text-align: left;
margin-left: 300px;
padding: 10px 0 30px 0;
}

.label-input {
margin-top: 25px;
}

.select-input{
    width: 200px;
    padding: 3px;
    margin: 5px;
    border: 1px solid grey;
}

.select-input-small{
    padding: 3px 15px;
    margin: 5px;
    border: 1px solid grey;
}

.select-input-small select {
    color: red;
    width: 150px;
    height: 100px;
}

textarea {
    margin: 5px;
    padding: 5px;
    border: 1px solid grey;
}
</style>
