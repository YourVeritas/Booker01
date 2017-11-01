new Vue({
    el: ".calendar",
    data: {
        myCalendar: '',
        items: [],
    },
    created () {
        this.createCalendar(2017, 10)
    },
    methods: {
        createCalendar(year, month) {

            var mon = month - 1;
            var d = new Date(year, mon);
      
            var clearDay = 0 //количество дней, которые нужно пропустить
            this.items[0] = []

            for (var i = 0; i < this.getDay(d); i++) {
                this.items[0].push({})
                clearDay ++
            }

            // ячейки календаря с датами
            var row = 0
            while (d.getMonth() == mon) {
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
            var day = date.getDay();
            if (day == 0) day = 7;
            return day - 1;
        }
    }
})
