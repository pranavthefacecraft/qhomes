import { create } from 'zustand';

const useHoverStore = create(set => ({
  hoveredPropertyId: null,
  isHovered: false,
  animatingPropertyId: null,
  shouldStopAnimation: false,
  setHoveredProperty: (id) => set({ 
    hoveredPropertyId: id, 
    isHovered: true, 
    animatingPropertyId: id,
    shouldStopAnimation: false 
  }),
  clearHoveredProperty: () => set({ 
    hoveredPropertyId: null, 
    isHovered: false,
    shouldStopAnimation: true 
  }),
  clearAnimation: () => set({ 
    animatingPropertyId: null,
    shouldStopAnimation: false 
  }),
}));

export default useHoverStore;