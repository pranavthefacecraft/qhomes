import { create } from 'zustand';

const useHoverStore = create(set => ({
  hoveredPropertyId: null,
  isHovered: false,
  setHoveredProperty: (id) => set({ hoveredPropertyId: id, isHovered: true }),
  clearHoveredProperty: () => set({ hoveredPropertyId: null, isHovered: false }),
}));

export default useHoverStore;