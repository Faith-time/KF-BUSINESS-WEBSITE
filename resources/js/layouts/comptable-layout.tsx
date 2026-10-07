import type { ReactNode } from 'react';
import WorkspaceLayout from '@/layouts/workspace-layout';
import { comptableNav } from '@/config/navigation';

export default function ComptableLayout({ children }: { children: ReactNode }) {
  return <WorkspaceLayout config={comptableNav}>{children}</WorkspaceLayout>;
}
