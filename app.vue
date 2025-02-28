<template>
  <div class="min-h-screen bg-gradient-to-br from-green-100 to-green-50">
    <div class="container mx-auto px-4 py-12">
      <!-- ส่วนหัว -->
      <header class="text-center mb-16">
        <h1 class="text-6xl font-extrabold text-green-800 mb-4">ราวตากผ้าอัตโนมัติ</h1>
        <p class="text-gray-600 text-lg">ควบคุมการตากผ้าได้ง่ายๆ ด้วยระบบอัตโนมัติ</p>
      </header>

      <div class="bg-white rounded-2xl shadow-lg p-10 max-w-3xl mx-auto">
        <section class="mb-12">
          <h2 class="text-3xl font-bold text-green-700 mb-8">ส่วนควบคุม</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <button 
              @click="sendCommand('1')" 
              :disabled="autoMode"
              class="py-4 px-6 bg-green-600 text-white text-lg rounded-xl font-semibold shadow-md hover:bg-green-700 transition duration-300 disabled:opacity-50">
              ตากผ้า
            </button>
            <button 
              @click="sendCommand('0')" 
              :disabled="autoMode"
              class="py-4 px-6 bg-red-600 text-white text-lg rounded-xl font-semibold shadow-md hover:bg-red-700 transition duration-300 disabled:opacity-50">
              เก็บผ้า
            </button>
            <button 
              @click="toggleAutoMode"
              class="py-4 px-6 bg-blue-600 text-white text-lg rounded-xl font-semibold shadow-md hover:bg-blue-700 transition duration-300">
              {{ autoMode ? 'โหมดควบคุมด้วยตนเอง' : 'โหมดอัตโนมัติ' }}
            </button>
          </div>
        </section>

        <section>
          <h2 class="text-3xl font-bold text-green-700 mb-8">สถานะ</h2>
          <div class="space-y-6">
            <div class="flex items-center justify-between p-5 bg-green-50 border-l-4 border-green-400 rounded-lg">
              <span class="text-gray-800 font-medium">สถานะการทำงาน:</span>
              <span class="px-4 py-2 bg-green-200 text-green-800 font-semibold rounded-full shadow">
                {{ status }}
              </span>
            </div>
            <div class="flex items-center justify-between p-5 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
              <span class="text-gray-800 font-medium">โหมด:</span>
              <span class="px-4 py-2 bg-blue-200 text-blue-800 font-semibold rounded-full shadow">
                {{ currentMode }}
              </span>
            </div>
            <div class="flex items-center justify-between p-5 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
              <span class="text-gray-800 font-medium">อุณหภูมิ & ความชื้น:</span>
              <span class="px-4 py-2 bg-yellow-200 text-yellow-800 font-semibold rounded-full shadow">
                {{ temperature }} °C / {{ humidity }} %
              </span>
            </div>
          </div>
        </section>

        <!-- ส่วนประวัติ -->
        <section class="mb-8 mt-8">
          <h2 class="text-3xl font-bold text-green-700 mb-8">ประวัติอุณหภูมิความชื้นและการตากผ้า</h2>
          <div class="flex gap-4">
            <div class="relative">
              <button 
                @click="toggleDropdown" 
                class="py-4 px-6 bg-yellow-600 text-white text-lg rounded-xl font-semibold shadow-md hover:bg-yellow-700 transition duration-300">
                ประวัติเซนเซอร์
              </button>
              <div v-if="dropdownVisible" class="absolute z-10 bg-white border rounded-lg shadow-lg w-full mt-2 max-h-80 overflow-y-auto">
                <ul>
                  <li v-for="history in historyData" :key="history.id" class="px-6 py-4 hover:bg-gray-200 cursor-pointer">
                    <p><strong>{{ history.timestamp }}</strong></p>
                    <p>Temperature: {{ history.temperature }} °C</p>
                    <p>Humidity: {{ history.humidity }} %</p>
                  </li>
                </ul>
              </div>
            </div>

            <div class="relative">
              <button 
              @click="toggleRackDropdown" 
              class="py-4 px-6 bg-purple-600 text-white text-lg rounded-xl font-semibold shadow-md hover:bg-purple-700 transition duration-300">
              ประวัติการตากผ้า
            </button>
            <div v-if="rackDropdownVisible" class="absolute z-10 bg-white border rounded-lg shadow-lg w-full mt-2 max-h-80 overflow-y-auto">
              <ul>
                <li v-for="rack in rackData" :key="rack.rack_id" class="px-6 py-4 hover:bg-gray-200 cursor-pointer">
                  <p><strong>Rack ID: {{ rack.rack_id }}</strong></p>
                  <p>Status: {{ rack.status }}</p>
                  <p>{{ rack.timestamp }}</p>
                </li>
              </ul>
            </div>
            </div>
          </div>
        </section>

        <!-- ส่วนค่าเฉลี่ย -->
        <section class="mb-12">
          <h2 class="text-3xl font-bold text-green-700 mb-8">ค่าเฉลี่ยอุณหภูมิและความชื้น</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-5 bg-green-50 border-l-4 border-green-400 rounded-lg">
              <span class="text-gray-800 font-medium">ค่าเฉลี่ยอุณหภูมิ:</span>
              <span class="px-4 py-2 bg-green-200 text-green-800 font-semibold rounded-full shadow">
                {{ averageTemperature }} °C
              </span>
            </div>
            <div class="flex items-center justify-between p-5 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
              <span class="text-gray-800 font-medium">ค่าเฉลี่ยความชื้น:</span>
              <span class="px-4 py-2 bg-blue-200 text-blue-800 font-semibold rounded-full shadow">
                {{ averageHumidity }} %
              </span>
            </div>
          </div>
        </section>
      </div>
    </div>

    <footer class="text-center bg-green-800 text-white py-8 mt-20">
      <p class="font-medium">Designed by Tanat Mitsanthia & Thanathip Sethachutkul</p>
    </footer>
  </div>
</template>

<script>
import mqtt from "mqtt";
import axios from "axios";

export default {
  data() {
    return {
      client: null,
      status: "Waiting...",
      autoMode: false,
      temperature: "--",
      humidity: "--",
      averageTemperature: "--",
      averageHumidity: "--",
      historyData: [],
      dropdownVisible: false,
      currentMode: "Manual",
      rackData: [],
      rackDropdownVisible: false,
    };
  },
  methods: {
    sendCommand(command) {
      if (this.client && this.client.connected && !this.autoMode) {
        this.client.publish("clothes/control", command);
      }
    },
    toggleAutoMode() {
      this.autoMode = !this.autoMode;
      this.client.publish("clothes/mode", this.autoMode ? "Auto" : "Manual");
    },

    toggleDropdown() {
      this.dropdownVisible = !this.dropdownVisible;
      if (this.dropdownVisible) {
        this.getHistory();
      }
    },
    toggleRackDropdown() {
      this.rackDropdownVisible = !this.rackDropdownVisible;
      if (this.rackDropdownVisible) {
        this.getRackHistory();
      }
    },
    async getHistory() {
      try {
        const response = await axios.get("http://192.168.65.54/api/getHistory.php");
        this.historyData = response.data;
        this.calculateAverages();
      } catch (error) {
        console.error("Error fetching history:", error);
      }
    },
    async getRackHistory() {
      try {
        const response = await axios.get("http://192.168.65.54/api/Rack.php");
        if (response.data.status === "success") {
          this.rackData = response.data.data.reverse(); // แสดงข้อมูลจากใหม่ไปเก่า
        } else {
          console.error("Error fetching rack history:", response.data.message);
        }
      } catch (error) {
        console.error("Error fetching Rack history:", error);
      }
    },
    calculateAverages() {
      let totalTemperature = 0;
      let totalHumidity = 0;
      let count = this.historyData.length;

      this.historyData.forEach((record) => {
        totalTemperature += parseFloat(record.temperature);
        totalHumidity += parseFloat(record.humidity);
      });

      this.averageTemperature = (totalTemperature / count).toFixed(2);
      this.averageHumidity = (totalHumidity / count).toFixed(2);
    },
    setupMqtt() {
      this.client = mqtt.connect("ws://broker.hivemq.com:8000/mqtt");

      this.client.on("connect", () => {
        this.client.subscribe("clothes/status");
        this.client.subscribe("clothes/webSensorData");
        this.client.subscribe("clothes/statusmode");
      });

      this.client.on("message", (topic, message) => {
        if (topic === "clothes/status") {
          this.status = message.toString();
        } else if (topic === "clothes/webSensorData") {
          try {
            const data = JSON.parse(message.toString());
            this.temperature = data.temp;
            this.humidity = data.humi;
          } catch (error) {
            console.error("Error parsing sensor data:", error);
          }
        } else if (topic === "clothes/statusmode") {
          this.currentMode = message.toString();
          this.autoMode = this.currentMode === "Auto";
        }
      });
    },
  },
  mounted() {
    this.setupMqtt();
    this.getRackHistory(); // โหลดข้อมูล Rack ทันทีเมื่อหน้าเว็บโหลด
  },
};
</script>

<style>
body {
  font-family: "Kanit", sans-serif;
  margin: 0;
  background-color: #c3fffb;
}
</style>
