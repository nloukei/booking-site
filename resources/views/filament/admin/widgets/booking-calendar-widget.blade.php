<x-filament-widgets::widget>
    <x-filament::section heading="Booking Calendar" description="View pending and approved bookings by date.">
        
        @php
            $bookingsData = $this->bookings;
        @endphp

        <div x-data="bookingCalendar()" x-init="initCalendar()" class="w-full">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h2 x-text="monthYear" class="text-lg font-semibold text-gray-900 dark:text-white"></h2>
                <div class="flex space-x-2">
                    <button @click="prevMonth" type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        &larr; Prev
                    </button>
                    <button @click="nextMonth" type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Next &rarr;
                    </button>
                </div>
            </div>

            <!-- Days of week -->
            <div class="grid grid-cols-7 gap-1 mb-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 text-sm">
                <template x-for="(blank, index) in blankDays" :key="'blank-'+index">
                    <div class="h-16 bg-gray-50 dark:bg-gray-800/50 rounded-lg"></div>
                </template>
                
                <template x-for="(day, index) in noOfDays" :key="'day-'+index">
                    <div 
                        @click="selectDate(day)"
                        :class="{
                            'ring-2 ring-primary-500': isToday(day),
                            'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer': true
                        }"
                        class="h-16 relative border border-gray-200 dark:border-gray-700 rounded-lg p-1 transition flex flex-col items-center justify-start"
                    >
                        <span x-text="day" class="font-medium text-gray-700 dark:text-gray-200"></span>
                        
                        <!-- Indicator -->
                        <template x-if="hasBookings(day)">
                            <div class="mt-1 flex flex-wrap justify-center gap-1">
                                <template x-for="b in getBookingsForDay(day)" :key="b.id">
                                    <div :class="b.status === 'approved' ? 'bg-success-500' : 'bg-warning-500'" class="w-2 h-2 rounded-full"></div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Selected Date Details -->
            <div x-show="selectedDate" class="mt-6 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50" style="display: none;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200" x-text="'Bookings for ' + selectedDateFormatted"></h3>
                    <button @click="selectedDate = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">&times; Close</button>
                </div>
                
                <div x-show="selectedBookings.length === 0" class="text-gray-500 dark:text-gray-400 text-sm">
                    No bookings for this date.
                </div>
                
                <div class="space-y-3">
                    <template x-for="b in selectedBookings" :key="b.id">
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="b.venue.name"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400" x-text="b.user.name + ' (' + b.start_time.substring(0,5) + ' - ' + b.end_time.substring(0,5) + ')'"></div>
                            </div>
                            <div>
                                <span x-text="b.status" 
                                    :class="b.status === 'approved' ? 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400' : 'bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400'"
                                    class="px-2 py-1 text-xs font-medium rounded-full capitalize"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('bookingCalendar', () => ({
                    month: '',
                    year: '',
                    noOfDays: [],
                    blankDays: [],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    bookings: @json($bookingsData),
                    selectedDate: null,
                    selectedBookings: [],
                    
                    initCalendar() {
                        let today = new Date();
                        this.month = today.getMonth();
                        this.year = today.getFullYear();
                        this.getNoOfDays();
                    },
                    
                    get monthYear() {
                        return this.monthNames[this.month] + ' ' + this.year;
                    },
                    
                    get selectedDateFormatted() {
                        if (!this.selectedDate) return '';
                        return new Date(this.selectedDate).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    },
                    
                    getNoOfDays() {
                        let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                        let dayOfWeek = new Date(this.year, this.month).getDay();
                        
                        let blankdaysArray = [];
                        for (let i = 1; i <= dayOfWeek; i++) {
                            blankdaysArray.push(i);
                        }
                        
                        let daysArray = [];
                        for (let i = 1; i <= daysInMonth; i++) {
                            daysArray.push(i);
                        }
                        
                        this.blankDays = blankdaysArray;
                        this.noOfDays = daysArray;
                    },
                    
                    formatDateForCompare(day) {
                        let m = (this.month + 1).toString().padStart(2, '0');
                        let d = day.toString().padStart(2, '0');
                        return `${this.year}-${m}-${d}`;
                    },
                    
                    hasBookings(day) {
                        let dateStr = this.formatDateForCompare(day);
                        return this.bookings[dateStr] !== undefined && this.bookings[dateStr].length > 0;
                    },
                    
                    getBookingsForDay(day) {
                        let dateStr = this.formatDateForCompare(day);
                        return this.bookings[dateStr] || [];
                    },
                    
                    selectDate(day) {
                        let dateStr = this.formatDateForCompare(day);
                        this.selectedDate = dateStr;
                        this.selectedBookings = this.getBookingsForDay(day);
                    },
                    
                    isToday(day) {
                        const today = new Date();
                        const d = new Date(this.year, this.month, day);
                        return today.toDateString() === d.toDateString();
                    },
                    
                    nextMonth() {
                        if (this.month === 11) {
                            this.month = 0;
                            this.year++;
                        } else {
                            this.month++;
                        }
                        this.getNoOfDays();
                        this.selectedDate = null;
                    },
                    
                    prevMonth() {
                        if (this.month === 0) {
                            this.month = 11;
                            this.year--;
                        } else {
                            this.month--;
                        }
                        this.getNoOfDays();
                        this.selectedDate = null;
                    }
                }))
            })
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
