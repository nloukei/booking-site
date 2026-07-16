@props(['venue' => null, 'isAdmin' => false])

@php
    // In Filament forms, the model is available via $getRecord()
    $currentVenue = $venue ?? (isset($getRecord) ? $getRecord() : null);
    
    $bookingsData = $currentVenue ? $currentVenue->bookings()
        ->whereIn('status', ['pending', 'approved', 'confirmed'])
        ->get()
        ->groupBy(function($booking) {
            return \Carbon\Carbon::parse($booking->date)->format('Y-m-d');
        }) : collect();
@endphp

<div x-data="venueMiniCalendar()" x-init="initCalendar()" class="w-full bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 x-text="monthYear" class="text-md font-semibold text-gray-900 dark:text-white"></h2>
        <div class="flex space-x-1">
            <button @click.prevent="prevMonth" type="button" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click.prevent="nextMonth" type="button" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- Days of week -->
    <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
        <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1 text-sm">
        <template x-for="(blank, index) in blankDays" :key="'blank-'+index">
            <div class="h-10 bg-gray-50 dark:bg-gray-800/50 rounded-md"></div>
        </template>
        
        <template x-for="(day, index) in noOfDays" :key="'day-'+index">
            <div 
                @click="selectDate(day)"
                :class="{
                    'ring-2 ring-primary-500': isToday(day),
                    'bg-gray-100 text-gray-400 dark:bg-gray-700/50 cursor-not-allowed': hasBookings(day) && !isAdmin,
                    'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer': (!hasBookings(day) || isAdmin) && !isPast(day),
                    'opacity-50 cursor-not-allowed': isPast(day)
                }"
                class="h-10 relative border border-gray-200 dark:border-gray-700 rounded-md p-1 transition flex flex-col items-center justify-center group"
            >
                <span x-text="day" class="font-medium" :class="{'line-through text-red-500': hasBookings(day) && !isAdmin, 'text-gray-700 dark:text-gray-200': !(hasBookings(day) && !isAdmin)}"></span>
                
                <!-- Indicator -->
                <template x-if="hasBookings(day)">
                    <div class="absolute bottom-1 w-1.5 h-1.5 rounded-full bg-red-500"></div>
                </template>
            </div>
        </template>
    </div>

    <div x-show="isAdmin" class="mt-3 text-xs text-gray-500 text-center">
        * Admin: Click a date to toggle block
    </div>

</div>

@once
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('venueMiniCalendar', () => ({
            month: '',
            year: '',
            noOfDays: [],
            blankDays: [],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            bookings: {},
            isAdmin: false,
            venueId: null,
            
            initCalendar() {
                this.bookings = @json($bookingsData);
                this.isAdmin = @json($isAdmin);
                this.venueId = @json($currentVenue ? $currentVenue->id : null);
                
                let today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.getNoOfDays();
            },
            
            get monthYear() {
                return this.monthNames[this.month] + ' ' + this.year;
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
            
            isPast(day) {
                let today = new Date();
                today.setHours(0,0,0,0);
                let d = new Date(this.year, this.month, day);
                return d < today;
            },
            
            selectDate(day) {
                if (this.isPast(day)) return;

                let dateStr = this.formatDateForCompare(day);
                
                if (this.isAdmin) {
                    this.toggleBlock(dateStr);
                } else {
                    // Pre-fill date in user form
                    const dateInput = document.getElementById('date');
                    if(dateInput && !this.hasBookings(day)) {
                        dateInput.value = dateStr;
                        dateInput.dispatchEvent(new Event('change'));
                    }
                }
            },

            async toggleBlock(dateStr) {
                try {
                    const response = await fetch(`/admin/venues/${this.venueId}/toggle-block`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ date: dateStr })
                    });
                    
                    const result = await response.json();
                    if(response.ok) {
                        if(result.status === 'blocked') {
                            this.bookings[dateStr] = [{status: 'approved'}];
                        } else {
                            delete this.bookings[dateStr];
                        }
                    } else {
                        alert(result.message || 'Error toggling block');
                    }
                } catch(e) {
                    alert('Network error');
                }
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
            },
            
            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.getNoOfDays();
            }
        }));
    });
</script>
@endonce
