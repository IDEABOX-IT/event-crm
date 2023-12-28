<template>
  <div v-if="flash.error" :class="flash.type === 'success' ? 'bg-green-500' : 'bg-red-500'">
    <p>{{ flash.error }}</p>
  </div>
  <form>
    <div class="fixed inset-0 flex items-center justify-center">
      <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold mb-4">Confirmação de Dados</h1>
        <p class="mb-2"><strong>Nome:</strong> {{ contact.first_name }} {{ contact.Suguimoto }}</p>
        <p class="mb-2"><strong>Email:</strong> {{ contact.email }}</p>
        <p class="mb-2"><strong>Telefone:</strong> {{ contact.phone }}</p>
        <p class="mb-2"><strong>Evento:</strong> {{ event.name }}</p>
        <p class="mb-4"><strong>Hora do evento:</strong> {{ event.time }}</p>
        <button @click="store" class="bg-green-500 text-white px-4 py-2 rounded mr-2">Confirmar</button>
        <Link class="bg-red-500 text-white px-4 py-2 rounded" href="/checkTicket">Voltar</Link>
      </div>
    </div>
  </form>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {Link} from "@inertiajs/inertia-vue3";

export default {
  components: {Link},
  props: {
    event: Object,
    contact: Object,
    qrCode: Object,
    flash: Object
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.qrCode.id,
        contact_id: this.qrCode.contact_id,
        qrCodePath: this.qrCode.qrCodePath,
        event_id: this.qrCode.event_id,
        isCheckinComplete: this.qrCode.isCheckinComplete,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/confirmQrCode')
    },
    cancelar() {
      Inertia.visit('/checkTicket');
    },
  },
};
</script>
