<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/events">Evento</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="event.deleted_at" class="mb-6" @restore="restore"> Esse evento foi deletado. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Nome" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Contato" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Endereço" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="Cidade" />
          <text-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="Província" />
          <select-input v-model="form.country" :error="form.errors.country" class="pb-8 pr-6 w-full lg:w-1/2" label="Pais">
            <option value="JA">Japǎo</option>
          </select-input>
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="Postal code" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!event.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Remover Evento</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Alterar Evento</loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">Clientes</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Nome</th>
          <th class="pb-4 pt-6 px-6">Endereço</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Telefone</th>
        </tr>
        <tr v-for="contact in event.contacts" :key="contact.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/contacts/${contact.id}/edit`">
              {{ contact.name }}
              <icon v-if="contact.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              {{ contact.city }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              {{ contact.phone }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/contacts/${contact.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="event.contacts.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No contacts found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    event: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.event.name,
        email: this.event.email,
        phone: this.event.phone,
        address: this.event.address,
        city: this.event.city,
        region: this.event.region,
        country: this.event.country,
        postal_code: this.event.postal_code,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/events/${this.organization.id}`)
    },
    destroy() {
      if (confirm('Tem certeza que deseja remover esse evento ?')) {
        this.$inertia.delete(`/events/${this.organization.id}`)
      }
    },
    restore() {
      if (confirm('tem certeza que deseja restaurar esse evento ?')) {
        this.$inertia.put(`/events/${this.organization.id}/restore`)
      }
    },
  },
}
</script>
