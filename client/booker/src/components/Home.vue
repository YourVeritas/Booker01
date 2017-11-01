<template>
<div class="booker">
    <div>
      <ul class="nav nav-tabs"  @click="changeView('viewCalendar')">
        <li class="head-logo">BOARDROOM BOOKER</li>
        <li role="presentation" class="active"><a href="#">Boardroom 1</a></li>
        <li role="presentation"><a href="#">Boardroom 2</a></li>
        <li role="presentation"><a href="#">Boardroom 3</a></li>
      </ul>
    </div>
    <div v-if="viewCalendar">
      <div class="clearfix">
        <div class="calendar ">
            <div class="date-box">
              <div class="data-list glyphicon glyphicon-chevron-left" v-on:click="minusMonth()"></div>
              <div class="data-select">{{year}} {{monthsItems[month]}}</div>
              <div class="data-list glyphicon glyphicon-chevron-right" v-on:click="plusMonth()"></div>
            </div>
            <table>
                <tr>
                  <th v-for="(day, index) in daysItems">
                    {{day}}
                  </th>
                <tr>
                <tr v-for="row in items">
                  <th v-for="day in row" class="cell">
                      {{day.data}}
                  </th>
                </tr>
            </table>
        </div>
        <div class="right-column">
          <div class="btn-right-column" @click="changeView('viewFormAddEvent')">Book it!</div>
          <div class="btn-right-column">Employee List</div>
        </div>
      </div>
      <div class="footer-options">
        <div v-on:click="firstMonday = !firstMonday; changeDayArr(viewFormAddEvent)">Format week</div>
      </div>
    </div>
    <div v-if="viewFormAddEvent && auth">
        <form-add-event></form-add-event>
    </div>
</div>
</template>
<script>
  import axios from 'axios'
  import formAddEvent from './FormAddEvent'

  export default {
    data: () => ({
      auth: true,
      viewCalendar: true,
      viewFormAddEvent: false,
      items: [],
      items2: [],
      items3: [],
      year: '',
      month: '',
      firstMonday: true,
      monthsItems: ['January','February','March','April','May','June','July','August','September','October','November','December'],
      daysItems: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
    }),
    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}},
    created () {
      this.year = new Date().getFullYear()
      this.month = new Date().getMonth()
      this.createCalendar()

    },
    methods:{
      sayHi(){
        console.log('Hello')
      },
      createCalendar() {
        var year = this.year
        var month = this.month

        var d = new Date(year, month);
        this.items= []
        this.items[0] = []

        for (var i = 0; i < this.getDay(d); i++) {
            console.log(d)
            this.items[0].push({}) //количество дней, которые нужно пропустить
        }

        // ячейки календаря с датами
        var row = 0
        while (d.getMonth() == month) {
            this.items[row].push({data: d.getDate()})

          if (this.getDay(d) % 7 == 6) { // вс, последний день - перевод строки
            row ++
            this.items[row] = []
          }
      
          d.setDate(d.getDate() + 1);
        }

        var self = this 
        setTimeout(function(){console.log(this.items)}, 1000)
      
    },
    getDay(date) { // получить номер дня недели, от 0(пн) до 6(вс)

        if (this.firstMonday === false){
          var day = date.getDay();
          return day;
        }
        else{
          var day = date.getDay();
          if (day == 0) day = 7;
          return day - 1;
        }
    },
    plusMonth(){
        if (this.month == 11){
          this.year += 1
          this.month = 0
        }
        else{
          this.month += 1
        }
        this.createCalendar()
    },
    minusMonth(){
        if (this.month == 0){
          this.year -= 1
          this.month = 11
        }
        else{
          this.month -= 1
        }
        this.createCalendar()
    },
    changeDayArr(){
      if (this.firstMonday == false)
        this.daysItems = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']
      else
        this.daysItems = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']

      this.createCalendar()
   },
    changeView(activeView){
      this.viewCalendar = false
      this.viewFormAddEvent = false
      this[activeView] = true
    }
  },
  components: {
     'form-add-event' : formAddEvent,
    } 
}

</script>
<style>
* {
    margin: 0;
    padding: 0;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

a :hover{
  text-decoration: none;
}

.header-logo{
cursor: pointer;
}

table {
    border-collapse: collapse;
}

td,th {
  border: 1px solid black;
  padding: 3px;
  text-align: center;
}

th {
  font-weight: bold;
  background-color: #F5F5F5;
}

.head-logo{
  margin: 0 100px 0 100px;
  font-size: 20px;
}

.calendar{
  margin: 20px 0 0 200px;
  float: left;
}

.cell{
  height: 100px;
  width: 110px;
}

.date-box {
  width: 810px;
  margin: 5px;
  text-align: left;
  padding-left: 265px;
}

.data-list{
cursor: pointer;
display: inline-block;
font-size: 20px;
margin: 5px;
}

.data-select{
  display: inline-block;
  text-align: center;
  font-size: 20px;
  margin: 5px;
  width: 150px;
}

.right-column{
  float:left;
  padding-top: 250px;
}

.btn-right-column{
  cursor: pointer;
  width: 200px;
  height: 30px;
  background-color: #696969;
  color:white;
  padding-top: 3px;
  font-size: 15px;
  margin: 5px;
  box-shadow: 0px 1px 1px 1px rgba(0, 0, 0, .2);
}

.footer-options{
  display:block;
  padding: 5px 0 50px 0;
}

.footer-options div{
  cursor: pointer;
}


</style>