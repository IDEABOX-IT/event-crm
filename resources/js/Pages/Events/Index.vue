<template>
  <div>
    <Head title="Eventos" />
    <h1 class="mb-8 text-3xl font-bold">Eventos</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Evento:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Disponível</option>
          <option value="only">Não disponível</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/events/create">
        <span>Adicionar</span>
        <span class="hidden md:inline">&nbsp;Evento</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Nome</th>
            <th class="pb-4 pt-6 px-6">Endereço</th>
            <th class="pb-4 pt-6 px-6" colspan="2">Contato</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="event in events.data" :key="event.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/events/${event.id}/edit`">
                {{ event.name }}
                <icon v-if="event.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
                {{ event.city }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/events/${event.id}/edit`" tabindex="-1">
                {{ event.phone }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/events/${event.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="events.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">Nenhum evento encontrado.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="events.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'
import * as events from "events";

export default {
  computed: {
    events() {
      return events
    }
  },
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    events: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/events', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
