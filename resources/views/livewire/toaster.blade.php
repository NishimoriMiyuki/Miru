<div>
    <style>
     .toast {
         position: fixed;
         bottom: 10px;
         left: 50%;
         transform: translateX(-50%);
         background-color: #222222;
         color: white;
         padding: .5rem;
         border-radius: .25rem;
         box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
         transition: all 0.5s ease;
     }
    </style>
    
    <div x-data="{ messages: [] }" 
         wire:ignore
         x-on:toaster.window="messages.push({id: Date.now(), text: $event.detail[0].message});"
         x-cloak>
         <template x-for="(message, index) in messages" :key="message.id">
              <div class="toast"
                   :style="`bottom: ${index * 60 + 10}px;`"
                   x-text="message.text"
                   x-init="setTimeout(() => { messages.splice(index, 1) }, 3000);">
              </div>
         </template>
    </div>
</div>