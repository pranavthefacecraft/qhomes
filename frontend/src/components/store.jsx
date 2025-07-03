import { create } from 'zustand';

const useHoverStore = create(set => ({
  hoveredPropertyId: null,
  setHoveredPropertyId: (id) => set({ hoveredPropertyId: id }),
}));

export default useHoverStore;