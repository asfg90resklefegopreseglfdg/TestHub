<template>
    <div class="border-top">
        <div v-if="duration !== 0">
            <h3>Осталось времени {{setTestTimer}}</h3>
        </div>
        <button
                class="btn container-fluid btn-success"
                @click="endTest">
            Закончить тест
        </button>

    </div>
</template>

<script>

    export default {
        props: {
            testId: {
                type: Number,
                required: true,
            },
            duration: {
                type: Number,
                required: true,
            },
        },

        data: function () {
            return {
                timerDisplay: '',
                timerLength: 1,
                date: new Date(),
            }
        },

        methods: {
            endTest: function () {
                this.$eventBus.$emit('end-test');
            },


            setTimerLengthFromLocalStorage: function () {
                let testEndTime;
                testEndTime = new Date(localStorage.getItem('durationTo' + this.testId));
                this.timerLength = Math.floor((testEndTime - this.date) / 1000);
            },

            setTimerLengthFromDuration: function () {
                this.timerLength = this.duration * 60;
            },

            timer: function () {
                this.timerLength--;
            },

            setTimer: function () {
                let durationTo;
                durationTo = (localStorage.getItem('durationTo' + this.testId)) ? (
                    new Date(localStorage.getItem('durationTo' + this.testId))
                ) : (
                    0
                );

                if (durationTo > this.date) {
                    this.setTimerLengthFromLocalStorage();
                } else if (durationTo === 0) {
                    this.date.setMinutes(this.date.getMinutes() + this.duration);
                    localStorage.setItem('durationTo' + this.testId, this.date);
                    this.setTimerLengthFromDuration();
                } else if (durationTo < this.date) {
                    this.$eventBus.$emit('uncorrect-time');
                }
                setInterval(this.timer, 1000)
            },
        },

        computed: {
            setTestTimer: function () {
                if (this.timerLength === 0) {
                   this.endTest();
                }
                let minutes = Math.floor(this.timerLength / 60);
                let seconds = this.timerLength % 60;
                if (seconds < 10) {
                    seconds = "0" + seconds;
                }
                if (minutes < 10) {
                    minutes = "0" + minutes;
                }
                return this.timerDisplay = minutes + ":" + seconds;
            },
        },

        mounted: function () {
            if (this.duration !== 0) {
                this.setTimer();
            }
        }
    }
</script>
















