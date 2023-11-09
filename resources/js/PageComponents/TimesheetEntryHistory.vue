<template>
    <div>
        <div class="px-6">
            <table class="table-auto table-hover table-focus w-full">
                <thead>
                    <tr>
                        <th class="text-left text-base md:text-lg font-semibold border-b-2 py-1 px-2">Person</th>
                        <th class="text-left text-base md:text-lg font-semibold border-b-2 py-1 px-2">Date</th>
                        <th class="text-left text-base md:text-lg font-semibold border-b-2 py-1 px-2">Description</th>
                        <th class="hidden md:table-cell text-right text-base md:text-lg font-semibold border-b-2 py-1 px-2">Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-sm md:text-base cursor-pointer hover:bg-gray-100" v-for="(timesheet_entry, index) in timesheet_entries_without_current" :key="index" @click="on_timesheet_select(index)">
                        <td class="border-t text-xs md:text-base py-2 px-2">{{ timesheet_entry.created_by.name }}</td>
                        <td class="border-t text-xs md:text-base py-2 px-2 whitespace-no-wrap">{{ $luxon(timesheet_entry.date, 'date_med') }}</td>
                        <td class="border-t text-xs md:text-base py-2 px-2" v-html="nl2br(timesheet_entry.description)"></td>
                        <td class="hidden md:table-cell text-right border-t text-xs md:text-base py-2 px-2">{{  timesheet_entry.hours == null ? timesheet_entry.hours : (timesheet_entry.hours).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';

    export default {
        props: {
            object: {
                type: Object,
                default: {},
            },
            
            view_only: {
                type: Boolean,
                default: false,
            },

            current_id: {
                type: Number,
                default: null,
            },
        },

        components: {
            JetButton,
            JetLabel,
        },

        methods: {
            on_timesheet_select (index) {
                if (!this.view_only && this.$page.user.role == 'admin')
                {
                    this.$inertia.visit(route('timesheets.timesheet_entry_edit', {'timesheet_entry': this.object.timesheet_entries[index].id}), {data: { 'timesheet_entry_edit_from': '/workorder/' + this.object.timesheet_entries[index].workorder_id + '/edit' }})
                }
            },

            nl2br (str, is_xhtml) {
                if (typeof str === 'undefined' || str === null) return '';
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }
        },

        computed: {
            timesheet_entries_without_current () {
                if (this.current_id === null) return this.object.timesheet_entries;
                else
                {
                    let timesheet_entries_without_current = [];
                    
                    this.object.timesheet_entries.forEach((timesheet_entry) => {
                        if (timesheet_entry.id !== this.current_id) timesheet_entries_without_current.push(timesheet_entry);
                    })
                    return timesheet_entries_without_current;
                }
            }
        }
    }
</script>

