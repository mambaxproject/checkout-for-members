<div id="chatWidget" class="fixed inset-0 pointer-events-none z-50">
    <div id="chatModal"
        class="fixed bottom-28 right-8 w-[390px] h-[675px] bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 pointer-events-auto opacity-0 translate-y-8 scale-95 transition-all duration-500 ease-out"
        style="pointer-events: none;">
        <div class="bg-gradient-to-r from-[#33cc33] to-[#33cc33] p-6 rounded-t-3xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div
                            class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <div id="headerStatus"
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Sales IA</h3>
                        <p id="statusText" class="text-white/70 text-sm">Online</p>
                    </div>
                </div>
                <button id="minimizeBtn"
                    class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200 backdrop-blur-sm">
                    <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="messagesArea"
            class="flex-1 h-[470px] overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50 to-white">
        </div>
        <div class="p-2 bg-white rounded-b-3xl border-t border-gray-100">
            <form id="messageForm" class="flex items-center space-x-3 max h-6">
                <div class="flex-1 relative max-h-10">
                    <input id="messageInput" type="text" placeholder="Digite sua mensagem..."
                        class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 focus:bg-white text-gray-800 placeholder-gray-500 rounded-2xl border border-gray-200 focus:border-lime-400 focus:ring-2 focus:ring-lime-400/20 transition-all duration-200 text-sm font-medium outline-none mt-7" />
                </div>
                <button id="sendBtn" type="submit"
                    class="w-12 h-12 bg-[#33CC33] hover:bg-[#28a428] text-white rounded-2xl transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 mt-16">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <div id="mobileOverlay"
        class="fixed inset-0 pointer-events-auto opacity-0 transition-opacity duration-300 md:hidden"
        style="pointer-events: none;"></div>
</div>
@vite('resources/js/ia-assistant.js')


